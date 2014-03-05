#!/bin/bash
set -e

DIR="$( cd "$( dirname "$0" )" && pwd)"
rsync -az --force --delete --progress $DIR/ mandala:/vagrant/ \
    --exclude '.git' \
    --exclude 'environment' \
    --exclude 'data' \
    --exclude 'public/thumbnails'

ssh mandala 'php /vagrant/update.php'
