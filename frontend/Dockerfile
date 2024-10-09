# ./frontend/Dockerfile

# Usar uma imagem base do Node.js
FROM node:14

# Definindo o diretório de trabalho
WORKDIR /app

# Copiando o package.json e o package-lock.json
COPY package*.json ./

# Instalando as dependências
RUN npm install

# Copiando o restante do código da aplicação
COPY . .

# Habilitando hot reloading
ENV CHOKIDAR_USEPOLLING=true

# Comando padrão para iniciar a aplicação
CMD ["npm", "start"]
