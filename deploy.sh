#!/bin/bash
set -e

DIR="$( cd "$( dirname "$0" )" && pwd)"
rsync -az --force --delete --progress $DIR/ mandala:/vagrant/ \
    --exclude '.git' \
    --exclude 'environment/.vagrant' \
    --exclude 'data' \
    --exclude 'public/data'

ssh mandala << EOF
php /vagrant/update.php
EOF
