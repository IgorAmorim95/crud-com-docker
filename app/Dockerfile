FROM node:10

WORKDIR /var/www/app

RUN npm install -g @vue/cli

COPY package*.json ./
RUN npm install
COPY . .

EXPOSE 8080

CMD ["npm", "run", "serve"]