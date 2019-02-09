#!/bin/bash
set -x
if [ $TRAVIS_BRANCH == 'master' ] ; then
    # Initialize a new git repo in _site, and push it to our server.
    cd _site
    git init

    git remote add deploy "deploy@162.243.162.94:/home/forge/repos/espinoso"
    git config user.name "Travis CI"
    git config user.email "leandro.jdl+travisCI@gmail.com"

    git add .
    git commit -m "Deploy"
    git push --force deploy master
else
    echo "Not deploying, since this branch isn't master."
fi
