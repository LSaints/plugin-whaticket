# Installation

You will need a working playSMS to begin with, let us assumed below items are your installation facts:

- Your playSMS web files is in `/var/www/html/playsms`
- Your playSMS database is `playsms`
- Your playSMS database username/password is `root/password`

Follow below steps in order:

1. Clone this repo to your playSMS server

   ```
   cd ~
   git clone https://github.com/LSaints/plugin-whaticket.git
   cd plugin-whaticket
   ls -l
   ```

2. Copy gateway to playSMS `plugin/gateway/`

   ```
   cp -rR web/plugin/gateway/whaticket /var/www/html/playsms/plugin/gateway/
   ```

3. Insert `web/plugin/gateway/whaticket/db/install.sql` to playSMS database

   ```
   mysql -uroot -p playsms < web/plugin/gateway/whaticket/db/install.sql
   ```

4. Restart `playsmsd`

   ```
   playsmsd restart
   playsmsd check
   ```
