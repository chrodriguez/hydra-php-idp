#!/bin/bash

docker run --rm -it -p 9010:9010 \
 --network hydra_default \
 oryd/hydra:v1.0.0-beta.9-alpine \
 token user \
    --fake-tls-termination \
    --port 9010 \
    --auth-url http://localhost:8000/oauth2/auth \
    --token-url http://hydra-server:4444/oauth2/token \
    --client-id sample-hydra-app-photo-resources \
    --client-secret some-secret \
    --scope openid,offline,photos.read
