FROM node:16 as api_platform_frontend

WORKDIR /home/node/

RUN curl -f https://get.pnpm.io/v6.16.js | node - add --global pnpm

# Files required by pnpm install
COPY .npmrc package.json pnpm-lock.yaml ./

RUN pnpm install esbuild

# Bundle app source
COPY . .


EXPOSE 4010

CMD ["pnpm", 'dev']