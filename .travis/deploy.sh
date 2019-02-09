#!/bin/bash
set -x
if [ $TRAVIS_BRANCH == 'master' ] ; then
    echo "162.243.162.94 ecdsa-sha2-nistp256 $SERVER_KEY" >> ~/.ssh/known_hosts
    ssh deploy@162.243.162.94 '/home/forge/espinoso-deploy.sh'
#    git remote add deploy "deploy@162.243.162.94:/home/forge/repos/espinoso"
#    git push deploy master
else
    echo "Not deploying, since this branch isn't master."
fi
