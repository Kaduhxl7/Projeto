# DressCode - Sistema de Brechós Online

## Grupo:
- Maria Eduarda Oliveira - Líder/22301275
- Maria Eduarda Rosa/22402080
- Kauan Alves Coelho/22302255
- Giovana Tassi/22403094
- Nicolas Peres/22302409
- Moises/22301313

## Requisitos do sistema implementados:

✅ **O sistema deve permitir o cadastro de brechós** - Feito

✅ **O dono do brechó deve poder cadastrar produtos à venda** - Feito

✅ **O sistema deve permitir a aplicação de filtros de busca (tamanho, cor, tipo)** - Feito

✅ **Os usuários devem poder visualizar detalhes dos produtos** - Feito

✅ **O usuário deve poder criar uma conta e gerenciar seu perfil** - Feito

✅ **Deve ser possível avaliar e comentar sobre os produtos comprados** - Feito

✅ **Os usuários devem poder salvar produtos em uma lista de desejos** - Feito

✅ **O sistema deve permitir a personalização das configurações da interface pelo usuário** - Feito

✅ **O site deve ter uma área dedicada a promoções e ofertas especiais** - Feito

✅ **O sistema deve garantir a segurança dos dados pessoais dos usuários e donos de brechós** - Feito

## Novas Funcionalidades Implementadas:

🆕 **Sistema de Cadastro Completo**
Separação entre Comprador (Usuário) e Vendedor (Brechó)
Campos: Nome, Sobrenome, Usuário, Celular
Upload de foto de perfil/banner
Campos condicionais para dados do brechó
Campos condicionais para endereço de entrega
Máscaras para celular e CEP
Busca automática de endereço via ViaCEP

🆕 **Sistema de Configurações**
Personalização de tema (claro/escuro)
Configuração de cores
Ajuste de tamanho de fonte
Preferências de layout

🆕 **Sistema de Segurança LGPD**
Log de tentativas de login
Controle de consentimentos
Exportação de dados pessoais
Exclusão de conta

## Como Executar:

1. Execute o arquivo start-server-windows.bat
2. Ou manualmente: cd public && php -S localhost:8000
3. Acesse: http://localhost:8000
4. Login de teste: teste@dresscode.com / 123456
