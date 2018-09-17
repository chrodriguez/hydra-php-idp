#!/bin/bash

docker run --rm -it -p 9010:9010 \
 --network hydra_default \
 oryd/hydra:v1.0.0-beta.9-alpine \
 token user --skip-tls-verify \
    --port 9010 \
    --auth-url http://localhost:8000/oauth2/auth \
    --token-url http://hydra-server:4444/oauth2/token \
    --client-id facebook-photo-backup \
    --client-secret some-secret \
    --scope openid,offline,photos.read
