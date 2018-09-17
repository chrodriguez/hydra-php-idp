#!/bin/bash

docker run --rm -it -e  HYDRA_ADMIN_URL=http://hydra-server:4445 \
 --network hydra_default \
 oryd/hydra:v1.0.0-beta.9-alpine \
 clients create --skip-tls-verify \
    --id sample-hydra-app-photo-resources \
    --secret some-secret \
    --grant-types authorization_code,refresh_token,client_credentials,implicit \
    --response-types token,code,id_token \
    --scope openid,offline,photos.read \
    --callbacks http://127.0.0.1:9010/callback

