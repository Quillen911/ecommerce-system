FROM node:20-slim

WORKDIR /app

COPY package*.json ./

RUN chown -R node:node /app

USER node

RUN npm install

COPY --chown=node:node . .

EXPOSE 3000

CMD ["npm", "run", "dev"]
