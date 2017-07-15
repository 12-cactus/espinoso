# Espinoso :: Telegram Bot

## Installation

### 1. Clone project

```bash
$ cd your-dev-path
$ git clone https://github.com/12-cactus/espinoso.git
```

### 2. Install && Configure

Instructions: [https://laravel.com/docs/5.4/homestead#installation-and-setup](https://laravel.com/docs/5.4/homestead#installation-and-setup)

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

### 3. Starting & Testing

```bash
$ cd your-path/Homestead
$ vagrant up
Homestead $ cd espinoso
Homestead $ composer install
Homestead $ phpunit test
```