# DressCode - Plataforma de Brechós Online

## 📋 Sobre o Projeto

DressCode é uma plataforma web para conectar brechós e consumidores interessados em moda sustentável e consciente. O projeto visa facilitar a compra e venda de roupas usadas, promovendo a economia circular na moda.

## 👥 Equipe

- **Maria Eduarda Oliveira** - Líder do Projeto
- **Maria Eduarda Rosa**
- **Kauan Alves Coelho**
- **Giovana Tassi**
- **Nicolas Peres**
- **Moises**

## 🚀 Funcionalidades Implementadas

### ✅ Concluídas
- [x] Sistema de cadastro e login de usuários
- [x] Página inicial com destaques e categorias
- [x] Catálogo de produtos femininos
- [x] Sistema de filtros (tamanho, cor, tipo, marca)
- [x] Visualização de detalhes dos produtos
- [x] Interface responsiva e moderna
- [x] Área de promoções e ofertas especiais

### 🔄 Em Desenvolvimento
- [ ] Cadastro de brechós
- [ ] Sistema de avaliações e comentários
- [ ] Lista de desejos
- [ ] Personalização da interface
- [ ] Sistema de segurança avançado
- [ ] Catálogos masculino e infantil

## 🛠️ Tecnologias Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 8+
- **Banco de Dados**: MySQL
- **Bibliotecas**: 
  - SweetAlert2 (alertas)
  - Swiper.js (carrossel)
  - Google Fonts

## 📁 Estrutura do Projeto

```
Projeto-main/
├── app/
│   ├── config/
│   │   └── database.php          # Configuração do banco
│   ├── controllers/
│   │   └── AuthController.php    # Controlador de autenticação
│   ├── models/
│   │   └── User.php             # Modelo de usuário
│   └── views/                   # Views (futuro)
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css        # Estilos principais
│   │   ├── js/
│   │   │   └── script.js        # Scripts JavaScript
│   │   └── images/              # Imagens do projeto
│   ├── index.php                # Página inicial
│   ├── login.php                # Página de login
│   ├── cadastro.php             # Página de cadastro
│   └── feminino.php             # Catálogo feminino
├── includes/
│   ├── header.php               # Cabeçalho reutilizável
│   └── footer.php               # Rodapé reutilizável
└── README.md                    # Este arquivo
```

## 🗄️ Banco de Dados

### Tabela: usuarios
```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🚀 Como Executar

1. **Clone o repositório**
   ```bash
   git clone [url-do-repositorio]
   cd Projeto-main
   ```

2. **Configure o servidor web**
   - Coloque os arquivos na pasta do seu servidor (htdocs, www, etc.)
   - Configure o Apache/Nginx para apontar para a pasta `public/`

3. **Configure o banco de dados**
   - Crie um banco MySQL chamado `dresscode`
   - Execute o script SQL para criar as tabelas
   - Ajuste as credenciais em `app/config/database.php`

4. **Acesse o projeto**
   - Abra o navegador e acesse `http://localhost/[pasta-do-projeto]/public/`

## 🎨 Design System

### Cores Principais
- **Primária**: #5e2b2b (Marrom escuro)
- **Secundária**: #a97474 (Marrom claro)
- **Background**: #f5f2ef (Bege claro)
- **Accent**: #b19a9a (Cinza rosado)

### Tipografia
- **Principal**: Inter (Google Fonts)
- **Títulos**: Martel Sans (Google Fonts)

## 📱 Responsividade

O projeto foi desenvolvido com design responsivo, adaptando-se a diferentes tamanhos de tela:
- Desktop (1200px+)
- Tablet (768px - 1199px)
- Mobile (até 767px)

## 🔒 Segurança

- Senhas criptografadas com `password_hash()`
- Validação de entrada de dados
- Proteção contra SQL Injection com PDO
- Sanitização de dados de formulário

## 🚧 Próximos Passos

1. Implementar sistema completo de produtos
2. Adicionar painel administrativo
3. Criar sistema de pagamentos
4. Implementar chat entre usuários
5. Adicionar sistema de notificações
6. Melhorar SEO e performance

## 📞 Contato

- **Email**: suportedresscode@dresscode.com
- **Telefone**: (11) 92348-9076

---

**DressCode** - Moda consciente e sustentável 🌱