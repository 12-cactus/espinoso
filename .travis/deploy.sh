#!/bin/bash
set -x
if [ $TRAVIS_BRANCH == 'master' ] ; then
    echo $SERVER_KEY >> ~/.ssh/known_hosts
    git remote add deploy "deploy@162.243.162.94:/home/forge/repos/espinoso"
    git push deploy master
else
    echo "Not deploying, since this branch isn't master."
fi
