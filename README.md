# talkly

[![Build Status](https://travis-ci.org/tolry/talkly.svg?branch=master)](https://travis-ci.org/tolry/talkly)

This is just a small pet project of mine, aiming for small developer teams who like to gather talk suggestions, rate on them, comment on them and add slides to them once the talk has been held.

what the first version should deliver:

- authentication/authorization mechanism (e.g. Datebase and LDAP)
- anyone can
    - add new suggestions
    - vote on suggestions
    - comment on suggestions
- after a talk is selected and performed, they can be
    - archived
    - slides added

As said before: this is not meant for everyone and far from stable. But if you like the idea and like to contribute or use it, go ahead.

technologies used:
- zurb foundation 5
- symfony
- doctrine orm
- reactjs

## Setup

```
git clone https://github.com/tolry/talkly.git
cd talkly
composer install
bin/console talkly:setup
```
