# Espinoso :: Telegram Bot

## Installation

### 1. Clone project

```bash
$ cd your-dev-path
$ git clone https://github.com/12-cactus/espinoso.git
```

### 2. Install environment && Configure

Install Homestead (or another http-server environment you want)

[https://laravel.com/docs/5.4/homestead#installation-and-setup](https://laravel.com/docs/5.4/homestead#installation-and-setup)

After install, go to Homestead directory and open `Homestead.yaml`.
You need to set map-folder, site and database like this:

```yaml
folders:
    - map: '~/your-dev-path/espinoso'
      to: '/home/vagrant/espinoso'
sites:
    - map: espinoso.dev
      to: '/home/vagrant/espinoso/public'
databases:
    - espinoso
```

Save & Exit

### 3. Start Homestead

```bash
$ cd Homestead
$ vagrant up
```

While provison & start, let's go to create our bot.

### 4. Create Espinoso Dev Bot

> If you like to learn more about bots, go to [https://core.telegram.org/bots](https://core.telegram.org/bots)

Open your telegram app and search **BotFather** (Telegram bot using for manager bots).

Type `/newbot` and follow instructions. You can use the name you prefer, but
it would be nice to use **EspinosoDevYOURINITIALSBot**.
When finish, **BotFather** will give you a token for your bot. Keep safe.
Then click con _t.me/YourBotName_

Your bot is still a dummy one. We need to configure the project & publish to internet.

#### 4.1 Install ngrok to publish

Enter Homestead via ssh

```bash
$ cd Homestead
$ vagrant up
$ vagrant ssh
```

From Homestead, download ngrok.

```bash
wget https://bin.equinox.io/c/4VmDzA7iaHb/ngrok-stable-linux-amd64.zip
unzip ngrok-stable-linux-amd64.zip
ngrok http 80
```

> If you prefer, you can go to [https://ngrok.com/download](https://ngrok.com/download)
> and follow instructions.

Now you are publishing to internet.

#### 4.2 Configure the bot

From Homestead go to espinoso folder, then
 
```bash
homestead $ cd espinoso
homestead $ cp .env.example .env
```

Open `.env` and search `TELEGRAM_BOT_TOKEN` key. Paste you bot token like:

```
TELEGRAM_BOT_TOKEN=123456:token
```

If don't have ngrok running, run it usign `ngrok http 80`. The, watch url forwarding.
We need to use always the https forwarding.

Copy the url and paste in `APP_URL` as `APP_URL=https://12217a95.ngrok.io`.

Then, we need to _set webhook_. We need to make a POST request, so we can't use a simply browser.
We can use [Postman](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop).

When postman is installed, open a tab, select POST method and paste ngrok https url with `/set-webhook`.

For example: `POST https://12217a95.ngrok.io/set-webhook`.

If you receive `[true]` is everything ok. If not, check ngrok console for errors.

### That's all

Now you can be able to interact with your espinoso bot. Open it in a chat and write _macri_.

It should response _Gato_.

**IMPORTANT!**

ngrok change use hash to make urls & it change every time you close server.

When you back to work and re-start ngrok, you need to go back to postman and 
request to `POST /set-webhook` again. Is not the best, but is free. If you know
a better way, please let we now.

If you like, you can use this artisan command:

```bash
$ php artisan espinoso:webhook-ngrok
```

which obtain ngrok url and make a POST request to set the webhook

### Testing

```bash
$ cd your-path/Homestead
$ vagrant up
Homestead $ cd espinoso
Homestead $ composer install
Homestead $ phpunit test
```