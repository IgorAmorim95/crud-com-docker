FROM node:10

WORKDIR /var/www/app

RUN npm install -g @vue/cli

ADD package.json /tmp/package.json
RUN cd /tmp && npm install
RUN cp -a /tmp/node_modules /var/www/app

WORKDIR /var/www/app

EXPOSE 8080

CMD ["npm", "run", "serve"]