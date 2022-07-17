<?php

namespace App\Http\Controllers;

use App\Card;
use App\Cart;
use App\Catalog;
use App\Client;
use App\Delivery;
use App\Order;
use App\Shop;
use App\Flower;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;

class BotController extends Controller
{
    public function index($token)
    {
        $bot = new Api($token);

        $updates = $bot->getWebhookUpdate();

        $message = last($updates);

        $shop = Shop::firstWhere('bot_token', $token);

        if ($shop == null) {
            $bot->removeWebhook();
            return 'this webhook was removed';
        }

        if (isset($message['message'])) {
            $client = $message['message']['chat'];

        } else {
            $client = $message['callback_query']['from'];
        }

        $this->checkClient($client, $shop->id);

        $this->checkMessage($bot, $shop, $message);
    }

    public function longpull()
    {
        $bot = new Api('5400048690:AAHPjrwhFzz1Ab3MxX8cikLK_FTMSrj9P2s');

        $response = $bot->getUpdates();

        $message = last($response);


        $shop = Shop::all()->first();


        if (isset($message['message'])) {
            $client = $message['message']['chat'];

        } elseif (isset($message['callback_query'])) {
            $client = $message['callback_query']['from'];
        }
        else {
            return 'error';
        }


        $this->checkClient($client, $shop->id);

        $this->checkMessage($bot, $shop, $message);
    }

    private function makeInlineKeyboard($data)
    {
        $reply_markup = Keyboard::make([
            'inline_keyboard' => $data,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
        ]);

        return $reply_markup;
    }

    private function makeKeyboard($data)
    {
        $reply_markup = Keyboard::make([
            'keyboard' => $data,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        return $reply_markup;
    }

    private function checkMessage($bot, $shop, $message)
    {
        if (isset($message['callback_query'])) {
            $this->checkCallback($bot, $shop, $message);
        } elseif (isset($message['message'])) {
            $client = $message['message']['chat'];
            $client_db = Client::where('username', $client['username'])->first();

            $message_text = $message['message']['text'];
            $chat_id = $message['message']['chat']['id'];

            if ($client_db != null && $client_db->session_id != null) {
                $this->updateClient($bot, $shop, $client_db, $chat_id, $message_text);
            }
            switch ($message_text) {
                case '/start':
                    $this->sendStartMessage($bot, $shop, $chat_id);
                    break;
                case 'Букеты':
                    $this->sendCatalogs($bot, $shop, $chat_id);
                    break;
                case 'Главное меню':
                    $this->sendStartMessage($bot, $shop, $chat_id);
                    break;
                case 'Корзина':
                    $this->sendCart($bot, $shop, $client, $chat_id);
                    break;
                case 'Настройки':
                    $this->sendSettings($bot, $shop, $chat_id);
                    break;
                case 'Имя':
                    $this->sendName($bot, $client_db, $chat_id, false);
                    break;
                case 'Телефон':
                    $this->sendPhone($bot, $client_db, $chat_id, false);
                    break;
                case 'Адрес':
                    $this->sendAddress($bot, $client_db, $chat_id, false);
                    break;
                case 'Доставка':
                    $this->sendDelivery($bot, $client_db, $chat_id, false);
                    break;
                case 'Статистика':
                    $this->sendStatistic($bot, $client_db, $chat_id, $shop);
                    break;
                case '/delFlower':
                    $this->showFlower($bot, $client_db, $chat_id, $shop);
                    break;
                case '/addFlower':
                    $this->addFlower($bot, $client_db, $chat_id);
                    break;
            }
        }
    }

    private function sendName($bot, $client, $chat_id, $next)
    {
        $data = [];
        $reply_markup = $this->makeKeyboard($data);
        if ($next) {
            $client->update(['session_id' => 'all_first_name']);
        } else {
            $client->update(['session_id' => 'first_name']);
        }
        $bot->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Введите имя:',
            'reply_markup' => $reply_markup,
        ]);
    }

    private function sendPhone($bot, $client, $chat_id, $next)
    {
        $data = [];
        $reply_markup = $this->makeKeyboard($data);
        if ($next) {
            $client->update(['session_id' => 'all_phone']);
        } else {
            $client->update(['session_id' => 'phone']);
        }
        $bot->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Введите телефон:',
            'reply_markup' => $reply_markup,
        ]);
    }

    private function sendAddress($bot, $client, $chat_id, $next)
    {
        $data = [];
        $reply_markup = $this->makeKeyboard($data);
        if ($next) {
            $client->update(['session_id' => 'all_address']);
        } else {
            $client->update(['session_id' => 'address']);
        }
        $bot->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Введите адрес:',
            'reply_markup' => $reply_markup,
        ]);
    }

    private function sendDelivery($bot, $client, $chat_id, $next)
    {
        $data = [];
        $shop  = $client->shop;

        foreach ($shop->deliveries as $delivery) {
           array_push($data,[$delivery->name]);
        }
        $reply_markup = $this->makeKeyboard($data);

        if ($next) {
            $client->update(['session_id' => 'all_delivery']);
        } else {
            $client->update(['session_id' => 'delivery']);
        }
        $bot->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Вариант доставки:',
            'reply_markup' => $reply_markup,
        ]);
    }

    private function getText($product, $shop)
    {
        $flowers = "";
        $check = true;
        foreach ($product->flowers as $flower) {
            $flower_db = Flower::where('id', $flower['id'])->first();
            if ($flower_db == null) $check = false;
            else {
                $flower_count = $flower['count'];
                $flowers .= "-  $flower_db->name, кол-во: $flower_count\n";
            }
        }
        if ($check) {
                $text = "<a href='" . $product->img . "'>" . $product->name . "</a>" . "\n" .
                        $product->description . "\n" .
                        $flowers . "\n" .
                        'Цена:' . $product->price . $shop->currency;
            }
        else {
            $text = "Данного букета нет в наличии";
        }

        return $text;
    }

    private function updateClient($bot, $shop, $client, $chat_id, $update_message)
    {
        $next = false;
        $update_field = $client->session_id;
        $all_session = explode("_", $update_field, 2);

        if ($update_message == 'all') {
            $next = true;
            $this->sendName($bot, $client, $chat_id, $next);
            $client->update(['session_id' => 'all_first_name']);
        } elseif ($all_session[0] == 'all') {
            $next = true;
            $client->update([$all_session[1] => $update_message]);

            if ($all_session[1] == 'delivery') {
                $this->sendOrder($bot, $shop, $client, $chat_id);
                $client->update(['session_id' => null]);
            }

        } elseif ($update_field == 'flower') {
            Flower::create([
                'name' => $update_message,
                'shop_id' => $shop->id,
            ]);
            $client->update(['session_id' => null]);
        }
        else {
            if ($update_field != null) {
                $client->update([$update_field => $update_message]);
            }
            $client->update(['session_id' => null]);
        }

        if ($next == true && $update_message != 'all') {
            switch ($all_session[1]) {
                case 'first_name':
                    $this->sendPhone($bot, $client, $chat_id, $next);
                    break;

                case 'phone':
                    $this->sendAddress($bot, $client, $chat_id, $next);
                    break;

                case 'address':
                    $this->sendDelivery($bot, $client, $chat_id, $next);
                    break;
            }
        } elseif ($next == false) {
            switch ($update_field) {
                case 'first_name':
                    $success_message = "Имя обновлено";
                    break;

                case 'phone':
                    $success_message = "Телефон обновлен.";
                    break;

                case 'address':
                    $success_message = "Адрес обновлен.";
                    break;

                case 'delivery':
                    $success_message = "";
                    break;

                case 'flower':
                    $success_message = "Цветок добавлен";
                    break;
            }

            $bot->sendMessage([
                'chat_id' => $chat_id,
                'text' => $success_message,
            ]);
        }

    }
    private function checkCallback($bot, $shop, $message)
    {
        $callback_query = $message['callback_query'];

        $chat_id = $callback_query['message']['chat']['id'];

        $client = $callback_query['from'];

        $message_id = $callback_query['message']['message_id'];

        $data = $callback_query['data'];


        if (strpos($data, 'successOrder') === 0) {
            $this->successOrder($bot, $shop, $client, $chat_id);

        }
        elseif (strpos($data, 'delFlower') === 0) {
            $this->delFlower($bot, $client, $chat_id , ltrim($data, 'delFlower'));
        }
        elseif (strpos($data, 's') === 0) {
            $this->sendProducts($bot, $shop, $data, $chat_id);
        } elseif (strpos($data, 'p') === 0) {
            $this->sendProduct($bot, $shop, $data, $chat_id);
        } elseif (strpos($data, 'add') === 0) {
            $this->addToCart($bot, $shop, $callback_query, $chat_id, $client);
        } elseif (strpos($data, 'back') === 0) {
            $this->updateCart($bot, $shop, $client, $chat_id, 'back', ltrim($data, 'back'), $message_id);
        } elseif (strpos($data, 'next') === 0) {
            $this->updateCart($bot, $shop, $client, $chat_id, 'next', ltrim($data, 'next'), $message_id);
        } elseif (strpos($data, 'uncount') === 0) {
            $this->updateCart($bot, $shop, $client, $chat_id, 'uncount', ltrim($data, 'uncount'), $message_id);
        } elseif (strpos($data, 'count') === 0) {
            $this->updateCart($bot, $shop, $client, $chat_id, 'count', ltrim($data, 'count'), $message_id);
        } elseif (strpos($data, 'delete') === 0) {
            $this->updateCart($bot, $shop, $client, $chat_id, 'delete', ltrim($data, 'delete'), $message_id);
        } elseif (strpos($data, 'newOrder') === 0) {
            $this->newOrder($bot, $shop, $client, $chat_id);
        }


    }

    private function sendStartMessage($bot, $shop, $chat_id)
    {
        $data = [
            ['Букеты', 'Корзина'], ['Главное меню'], ['Настройки'],
        ];
        $keyboard = $this->makeKeyboard($data);

        $bot->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Добро пожаловать!',
            'reply_markup' => $keyboard,
        ]);
    }

    private function sendSettings($bot, $shop, $chat_id)
    {
        $data = [
            ['Имя', 'Телефон'], ['Адрес'], ['Главное меню'],
        ];
        $keyboard = $this->makeKeyboard($data);

        $bot->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Настройки',
            'reply_markup' => $keyboard,
            'one_time_keyboard' => true,
        ]);
    }

    private function sendCatalogs($bot, $shop, $chat_id)
    {
        $catalogs = $shop->catalogs;

        $sections = $catalogs->pluck('section1')->unique();
        $data = [];

        foreach ($sections as $section) {
            array_push($data, [Keyboard::inlineButton(['callback_data' => 's' . $section, 'text' => $section])]);
        }
        $keyboard = $this->makeInlineKeyboard($data);

        $bot->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Букеты:',
            'reply_markup' => $keyboard,
        ]);

    }

    private function sendProducts($bot, $shop, $message, $chat_id)
    {
        $catalogs = $shop->catalogs;
        $products = $catalogs->where('section1', ltrim($message, 's'))->pluck('name');
        $data = [];
        foreach ($products as $product) {
            array_push($data, [Keyboard::inlineButton(['callback_data' => 'p' . $product, 'text' => $product])]);
        }
        $keyboard = $this->makeInlineKeyboard($data);

        $bot->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Выберите товар',
            'reply_markup' => $keyboard,
        ]);
    }

    private function sendProduct($bot, $shop, $message, $chat_id)
    {
        $catalogs = $shop->catalogs;
        $product = $catalogs->where('name', ltrim($message, 'p'))->first();

        $data = [
            [Keyboard::inlineButton(['callback_data' => 'add' . $product->id, 'text' => 'Добавить в корзину'])],
        ];

        $keyboard = $this->makeInlineKeyboard($data);

        $text = $this->getText($product, $shop);

        if ($text == "Данного букета нет в наличии") $data = [];

        $bot->sendMessage([
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text,
            'reply_markup' => $keyboard,
        ]);

    }
    private function makeKeyboardForCart($product_id, $back, $next, $callback_message)
    {
        $cart = Cart::where('catalog_id', $product_id)->first();
        $data = [
            [Keyboard::inlineButton(['callback_data' => 'delete' . $product_id, 'text' => '❌']),
                Keyboard::inlineButton(['callback_data' => 'uncount' . $callback_message, 'text' => '🔻']),
                Keyboard::inlineButton(['callback_data' => 'amount', 'text' => $cart->amount]),
                Keyboard::inlineButton(['callback_data' => 'count' . $callback_message, 'text' => '🔺'])],
            [Keyboard::inlineButton(['callback_data' => 'back' . $back, 'text' => '◀️']),
                Keyboard::inlineButton(['callback_data' => '1', 'text' => $callback_message + 1 . '/' . $cart->count()]),
                Keyboard::inlineButton(['callback_data' => 'next' . $next, 'text' => '▶️'])],
            [Keyboard::inlineButton(['callback_data' => 'newOrder' . $next, 'text' => 'Заказать букет (ы)'])],
        ];

        $keyboard = $this->makeInlineKeyboard($data);
        return $keyboard;
    }
    private function updateCart($bot, $shop, $client_name, $chat_id, $callback, $callback_message, $message_id)
    {
        $client = Client::where(['username' => $client_name['username']])->first();
        $cart = $client->cart;

        $count = $cart->count();

        $callback_message = intval($callback_message);

        if ($count > $callback_message + 1) {
            $next = $callback_message + 1;
            $back = $callback_message - 1;

        } else if ($callback_message == 0) {
            $back = $count - 1;
        } else {
            $next = 0;
            $back = $callback_message - 1;
        }

        switch ($callback) {
            case 'delete':
                if ($count > 1) {
                    Cart::where('catalog_id', $callback_message)->delete();
                } else {
                    Cart::where('catalog_id', $callback_message)->delete();
                    $this->sendCatalogs($bot, $shop, $chat_id);
                }
                $callback_message = 0;

                $product = Catalog::where(['id' => $cart[$callback_message]->catalog_id])->first();

                $keyboard = $this->makeKeyboardForCart($product->id, $back, $next, $callback_message);

                $text = $this->getText($product, $shop);

                $bot->editMessageText([
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'parse_mode' => 'HTML',
                    'text' => $text,
                    'reply_markup' => $keyboard,
                ]);
                break;

            case 'count':

                $cart[$callback_message]->update(['amount' => $cart[$callback_message]->amount + 1]);
                $product = Catalog::where(['id' => $cart[$callback_message]->catalog_id])->first();

                $keyboard = $this->makeKeyboardForCart($product->id, $back, $next, $callback_message);

                $text = $this->getText($product, $shop);

                $bot->editMessageText([
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'parse_mode' => 'HTML',
                    'text' => $text,
                    'reply_markup' => $keyboard,
                ]);

                break;

            case 'uncount':

                if ($cart[$callback_message]->amount > 1) {
                    $cart[$callback_message]->update(['amount' => $cart[$callback_message]->amount - 1]);
                }

                $product = Catalog::where(['id' => $cart[$callback_message]->catalog_id])->first();

                $keyboard = $this->makeKeyboardForCart($product->id, $back, $next, $callback_message);

                $text = $this->getText($product, $shop);

                $bot->editMessageText([
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'parse_mode' => 'HTML',
                    'text' => $text,
                    'reply_markup' => $keyboard,
                ]);

                break;

            case 'back':

                $product = Catalog::where(['id' => $cart[intval($callback_message)]->catalog_id])->first();

                $keyboard = $this->makeKeyboardForCart($product->id, $back, $next, $callback_message);

                $text = $this->getText($product, $shop);

                $bot->editMessageText([
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'parse_mode' => 'HTML',
                    'text' => $text,
                    'reply_markup' => $keyboard,
                ]);

                break;

            case 'next':

                $product = Catalog::where(['id' => $cart[intval($callback_message)]->catalog_id])->first();

                $keyboard = $this->makeKeyboardForCart($product->id, $back, $next, $callback_message);

                $text = $this->getText($product, $shop);

                $bot->editMessageText([
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'parse_mode' => 'HTML',
                    'text' => $text,
                    'reply_markup' => $keyboard,
                ]);

                break;

        }

    }
    private function sendCart($bot, $shop, $client_name, $chat_id)
    {
        $client = Client::where(['username' => $client_name['username']])->first();
        $cart = $client->cart;
        $count = $cart->count();
        if ($count > 1) {
            $next = 1;
            $back = 0;
        } else {
            $next = 0;
            $back = 0;
        }
        if ($count < 1) {
            $bot->sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Корзина пуста.',
            ]);
            return;
        } else {
            $product = Catalog::where(['id' => $cart[0]->catalog_id])->first();
        }
        $data = [
            [Keyboard::inlineButton(['callback_data' => 'delete' . $product->id, 'text' => '❌']),
                Keyboard::inlineButton(['callback_data' => 'uncount' . $product->id, 'text' => '🔻']),
                Keyboard::inlineButton(['callback_data' => 'amount' . $product->id, 'text' => $cart[0]->amount]),
                Keyboard::inlineButton(['callback_data' => 'count' . $product->id, 'text' => '🔺'])],
            [Keyboard::inlineButton(['callback_data' => 'back' . $back, 'text' => '◀️']),
                Keyboard::inlineButton(['callback_data' => '1', 'text' => '1/' . $cart->count()]),
                Keyboard::inlineButton(['callback_data' => 'next' . $next, 'text' => '▶️'])],
            [Keyboard::inlineButton(['callback_data' => 'newOrder', 'text' => 'Заказать букет (ы)'])],
        ];

        $keyboard = $this->makeInlineKeyboard($data);

        $text = $this->getText($product, $shop);

        $bot->sendMessage([
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text,
            'reply_markup' => $keyboard,
        ]);
    }

    private function addToCart($bot, $shop, $callback_query, $chat_id, $client_name)
    {
        $client = Client::where(['username' => $client_name['username']])->first();

        $catalog = Catalog::where('id', ltrim($callback_query['data'], 'add'))->first();

        $old_cart = Cart::where('catalog_id', ltrim($callback_query['data'], 'add'))->first();

        if ($old_cart != null && $old_cart->client_id == $client->id) {
            $old_cart->update(['amount' => $old_cart->amount + 1]);
        } else {
            $cart = new Cart;

            $cart->client_id = $client->id;

            $cart->shop_id = $shop->id;

            $cart->catalog_id = $catalog->id;

            $cart->amount = 1;

            $cart->save();
        }
        $bot->answerCallbackQuery([
            'callback_query_id' => $callback_query['id'],
            'text' => 'Корзина обновлена',
            'cache_time' => 1,
        ]);
    }

    private function checkClient($client, $shop_id)
    {
        $old_client = Client::where('username', $client['username'])->first();

        if ($old_client != null) {
            $old_client->touch();
        } else {
            $new_client = new Client;

            $new_client->telegram_id = $client['id'];
            $new_client->first_name = $client['first_name'];
            if (isset($client['last_name'])) {
                $new_client->last_name = $client['last_name'];
            }
            $new_client->username = $client['username'];
            $new_client->shop_id = $shop_id;

            $new_client->save();
        }
    }

    private function newOrder($bot, $shop, $client, $chat_id)
    {
        $client = Client::where('username', $client['username'])->first();

        $this->updateClient($bot, $shop, $client, $chat_id, 'all');

    }

    private function sendOrder($bot, $shop, $client, $chat_id)
    {
        $sum_all = 0;
        $products = "";

        foreach ($client->cart as $key => $cart) {
            $i = $key + 1;
            $product = $cart->product;
            $sum = $product->price * $cart->amount;
            $sum_all += $sum;
            $products .= "$i) $product->name — $cart->amount шт. = $sum \n";
        }

        $text = "Общая сумма: " . $sum_all . $shop->currency . "\n" .
        "Получатель: " . $client->first_name . "\n" .
        "Телефон: " . $client->phone . "\n" .
        "Адрес доставки: " . $client->address . "\n \n" .
        "Букеты: \n" .
        $products . "\n" .
        "Доставка: " . $client->delivery;

        $data = [
            [Keyboard::inlineButton(['callback_data' => 'successOrder', 'text' => 'Подтвердить и отправить'])],
            [Keyboard::inlineButton(['callback_data' => 'newOrder', 'text' => 'Изменить'])],
        ];

        $keyboard = $this->makeInlineKeyboard($data);

        $client->update(['session_id' => null]);

        $bot->sendMessage([
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text,
            'reply_markup' => $keyboard,
        ]);
    }

    private function successOrder($bot, $shop, $client, $chat_id)
    {
        $client = Client::where('username', $client['username'])->first();
        $sum_all = 0;
        $products = "";
        $delivery = Delivery::where('name', $client->delivery)->first();
        foreach ($client->cart as $key => $cart) {
            $i = $key + 1;
            $product = $cart->product;
            $sum = $product->price * $cart->amount;
            $sum_all += $sum;
            $products .= "$i) $product->name — $cart->amount шт. = $sum \n";
        }

        $cards = Card::where('shop_id', $shop->id)->get();

        $payments = "";

        foreach ($cards as $card) {
            $payments .= "$card->name \n";
        }

        $text = "Заказ:  \n" .
            "Общая сумма: " . $sum_all . $shop->currency . "+" . $delivery->price . $shop->currency . ("=" . ($sum_all + $delivery->price)) . $shop->currency . "\n" .
            "Получатель: " . $client->first_name . "\n" .
            "Телефон: " . $client->phone . "\n" .
            "Адрес доставки: " . $client->address . "\n \n" .
            "Букеты: \n" .
            $products . "\n" .
            "Доставка: " . $client->delivery. "\n \n" .
            "Оплата на карту: \n" .
            $payments;

        $data = [['Главное меню']];

        $keyboard = $this->makeKeyboard($data);

        foreach ($client->cart as $product) {
            Order::create([
                'active' => 1,
                'client_id' => $product->client_id,
                'shop_id' => $product->shop_id,
                'catalog_id' => $product->catalog_id,
                'amount' => $product->amount,
                'delivery_id' => $delivery->id,
            ]);
            $product->delete();

        }
        $bot->sendMessage([
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'reply_markup' => $keyboard,
            'text' => $text,
        ]);


        return 0;
    }

    private function sendStatistic($bot, $client_db, $chat_id, $shop) {
        if (!$client_db->is_notify) {
            return 0;
        }
        $orders = Order::where('shop_id', $shop->id)->get();

        $text = "Статистика заказов: \n";

        foreach ($orders as $order) {
            $client = $order->client;
            $product = $order->product;
            $text .= "
            Id: $order->id \n
            Имя, Телефон, Адрес: $client->first_name  $client->phone   $client->address \n
            Доставка: $client->delivery \n
            Букет: $product->name \n
            Кол-во: $order->amount \n
            Цена (без учета доставки): $order->amount * $product->price";

        }

        $bot->sendMessage([
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text,
        ]);
    }

    private function addFlower($bot, $client_db, $chat_id) {
        if ($client_db->role != 'admin' && $client_db->role != 'flower') {
            return 0;
        }
        $client_db->update(['session_id' => 'flower']);

        $bot->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Введите название цветка:'
        ]);
    }

    private function showFlower($bot, $client_db, $chat_id, $shop) {
        if ($client_db->role != 'admin' && $client_db->role != 'flower') {
            return 0;
        }
        $data = [];

        $flowers = Flower::where('shop_id', $shop->id)->get();

        foreach ($flowers as $flower) {
            array_push($data, [Keyboard::inlineButton(['callback_data' => 'delFlower' . $flower->id, 'text' => $flower->name])]);
        }

        $keyboard = $this->makeInlineKeyboard($data);
        $bot->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Выберите цветок который хотите удалить:',
            'reply_markup' => $keyboard,
            ]);
      }

    private function delFlower($bot, $client, $chat_id, $flower_id) {
        $client_db = Client::where('username', $client['username'])->first();
        if ($client_db->role != 'admin' && $client_db->role != 'flower') {
            return 0;
        }

        $flower = Flower::where('id', $flower_id)->first();
        $flower->delete();

        $bot->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Цветок успешно удален'
        ]);
    }


    public function mailing(Request $request)
    {
        $users = Client::all();

        $token = auth()->user()->current_shop->bot_token;

        $bot = new Api($token);

        foreach ($users as $user) {
            $bot->sendMessage([
                'chat_id' => $user->telegram_id,
                'parse_mode' => 'HTML',
                'text' => $request->text,
            ]);
        }

        return redirect()->back();
    }
}
