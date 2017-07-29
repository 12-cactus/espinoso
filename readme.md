[![Build Status](https://travis-ci.org/12-cactus/espinoso.svg?branch=master)](https://travis-ci.org/12-cactus/espinoso)
[![Code Climate](https://codeclimate.com/github/12-cactus/espinoso/badges/gpa.svg)](https://codeclimate.com/github/12-cactus/espinoso)
[![Test Coverage](https://codeclimate.com/github/12-cactus/espinoso/badges/coverage.svg)](https://codeclimate.com/github/12-cactus/espinoso/coverage)
[![Issue Count](https://codeclimate.com/github/12-cactus/espinoso/badges/issue_count.svg)](https://codeclimate.com/github/12-cactus/espinoso)

# Espinoso :: Telegram Bot

## Installation

### 1. Clone project

```bash
$ cd ~/your-dev-path
$ git clone https://github.com/12-cactus/espinoso.git
```

### 2. Create your own Espinoso Dev Bot

> If you like to learn more about bots, go to [https://core.telegram.org/bots](https://core.telegram.org/bots)

Open your telegram app and search **BotFather** (Telegram bot using for manager bots).

Type `/newbot` and follow instructions. You can use the name you prefer, but
it would be nice to use **EspinosoDevYOURINITIALSBot**.
For example, if your name is _John Doe_, your bot could be called **EspinosoDevJDBot**.
When finish, **BotFather** will give you a token for your bot, save it for later.
**BotFather** should also given you a link like this _t.me/EspinosoDevYOURINITIALSBot_, click on it
to open a chat with your bot.

### 3. Install environment

First install [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
and [Vagrant](https://www.vagrantup.com/downloads.html).
Then install Homestead (below is a quickly installation, if you want
a better understanding, go to [Laravel documentation](https://laravel.com/docs/5.4/homestead#installation-and-setup))

```bash
$ vagrant box add laravel/homestead
```

When finish (it will take some time)

```bash
$ cd ~
$ git clone https://github.com/laravel/homestead.git Homestead
$ cd Homestead
$ git checkout v5.4.0
$ bash init.sh
```

After install, you need to configure Homestead. Open `Homestead.yaml`
and edit `folders`, `sites` & `databases` with something like this:

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

Save & Exit. Then you need to add site to your hosts file.
Open `/etc/hosts` and add this line to the end of file

```
192.168.10.10 espinoso.dev
```

### 4. Start Homestead & Init your Bot

Enter Homestead

```bash
$ cd ~/Homestead
$ vagrant up
$ vagrant ssh
```

Inside Homestead

```bash
homestead:$ ngrok http espinoso.dev:80
```

It will open a black _ngrok_ window. It tell you the url to use and you can view every http request.

To associate your bot with your _ngrok_ site, you need to open a new terminal

```bash
$ cd ~/Homestead
$ vagrant ssh
homestead:$ cd espinoso
homestead:$ cp .env.example .env
homestead:$ composer install
```

Open `.env` and search `TELEGRAM_BOT_TOKEN` key.

Copy your **saved token** and paste into var, like this: `TELEGRAM_BOT_TOKEN=123456:ABCDEF`

Finally, you need to associate _ngrok_ service as webhook. So, just run

```bash
# This command set ngrok publish services as webhook
homestead:$ artisan espinoso:webhook-ngrok
```

Now your bot is ready.

### That's all

Now you can be able to interact with your espinoso bot. Open it in a chat and write _macri_.

It should response _Gato_.

**IMPORTANT!**

_ngrok_ change url every time you restart server.

For daily usage or when you restart Homestead or _ngrok_, you need to re-associate them.

Terminal 1:

```
$ cd ~/Homestead && vagrant up && vagrant ssh
homestead:$ ngrok http espinoso.dev:80
```

Terminal 2:

```bash
$ cd ~/Homestead && vagrant ssh
homestead:$ cd espinoso
homestead:$ artisan espinoso:webhook-ngrok
```

### Testing

```bash
homestead:$ cd espinoso
homestead:$ composer install
homestead:$ phpunit test
```