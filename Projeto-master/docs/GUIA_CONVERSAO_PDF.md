# 📄 Guia de Conversão para PDF

## Arquivos Criados

Foram criados 3 arquivos HTML com a apresentação completa do projeto DressCode:

1. `APRESENTACAO_DRESSCODE.html` - Páginas 1-7 (Capa, Índice, Visão Geral, MVC, Repository, Singleton, Factory)
2. `APRESENTACAO_DRESSCODE_PARTE2.html` - Páginas 8-12 (Observer, Strategy+Decorator, Integração, Funcionalidades)
3. `APRESENTACAO_DRESSCODE_PARTE3.html` - Páginas 13-20 (Funcionalidades cont., Estrutura, Comparação, Métricas, Conclusão, Referências)

## 🖨️ Como Converter para PDF

### Opção 1: Navegador (Mais Fácil)

1. Abra cada arquivo HTML no navegador (Chrome, Firefox, Edge)
2. Pressione `Ctrl + P` (Windows) ou `Cmd + P` (Mac)
3. Selecione "Salvar como PDF" como destino
4. Configure:
   - Margens: Padrão
   - Escala: 100%
   - Orientação: Retrato
   - Incluir cabeçalhos e rodapés: Não
5. Clique em "Salvar"

**Resultado:** 3 arquivos PDF separados

### Opção 2: Mesclar em um Único PDF

#### Windows:
1. Instale o **PDFtk** ou use **Adobe Acrobat**
2. Ou use site online: https://www.ilovepdf.com/pt/juntar_pdf
3. Faça upload dos 3 PDFs na ordem correta
4. Clique em "Mesclar PDF"

#### Linux:
```bash
# Instalar pdftk
sudo apt-get install pdftk

# Mesclar PDFs
pdftk APRESENTACAO_DRESSCODE_parte1.pdf \
      APRESENTACAO_DRESSCODE_parte2.pdf \
      APRESENTACAO_DRESSCODE_parte3.pdf \
      cat output APRESENTACAO_DRESSCODE_COMPLETA.pdf
```

### Opção 3: Ferramentas Online

1. **Smallpdf**: https://smallpdf.com/pt/html-para-pdf
2. **HTML2PDF**: https://html2pdf.com/
3. **CloudConvert**: https://cloudconvert.com/html-to-pdf

### Opção 4: Linha de Comando (wkhtmltopdf)

```bash
# Instalar wkhtmltopdf
# Windows: baixar de https://wkhtmltopdf.org/downloads.html

# Converter cada arquivo
wkhtmltopdf APRESENTACAO_DRESSCODE.html parte1.pdf
wkhtmltopdf APRESENTACAO_DRESSCODE_PARTE2.html parte2.pdf
wkhtmltopdf APRESENTACAO_DRESSCODE_PARTE3.html parte3.pdf

# Mesclar (usando pdftk)
pdftk parte1.pdf parte2.pdf parte3.pdf cat output APRESENTACAO_COMPLETA.pdf
```

## 📋 Conteúdo da Apresentação

### Páginas 1-7 (Arquivo 1)
- Capa com título e equipe
- Índice completo
- Visão geral do projeto
- Arquitetura MVC explicada
- Repository Pattern
- Singleton Pattern
- Factory Method Pattern

### Páginas 8-12 (Arquivo 2)
- Observer Pattern
- Strategy + Decorator Pattern
- Implementação dos padrões
- Integração entre padrões
- Funcionalidades do sistema

### Páginas 13-20 (Arquivo 3)
- Funcionalidades (continuação)
- Estrutura de diretórios
- Conexão MVC + Repository + GoF
- Benefícios da arquitetura
- Comparação antes/depois
- Métricas do projeto
- Conclusão
- Referências

## 🎨 Características do Design

- **Formato:** A4 (210mm x 297mm)
- **Cores:** Tema marrom (#5e2b2b) do DressCode
- **Tipografia:** Segoe UI, profissional e legível
- **Elementos:** Tabelas, diagramas, código formatado
- **Estilo:** Acadêmico e profissional

## ✅ Checklist de Qualidade

Antes de apresentar, verifique:

- [ ] Todos os 3 arquivos HTML abrem corretamente
- [ ] Imagens e formatação estão corretas
- [ ] Código está legível e bem formatado
- [ ] Tabelas estão alinhadas
- [ ] Cores e estilos estão consistentes
- [ ] PDF gerado está completo (20 páginas)
- [ ] Texto está sem erros de digitação
- [ ] Diagramas estão claros

## 💡 Dicas para Apresentação

1. **Imprima uma cópia** para ter como referência
2. **Teste o PDF** em diferentes dispositivos
3. **Prepare notas** para cada seção
4. **Destaque os padrões GoF** - são o diferencial
5. **Mostre código real** do projeto durante a apresentação
6. **Prepare exemplos práticos** de cada padrão

## 🚀 Apresentação Oral Sugerida

### Introdução (2 min)
- Apresentar equipe
- Visão geral do DressCode
- Objetivos do projeto

### Arquitetura (5 min)
- Explicar MVC
- Mostrar Repository Pattern
- Demonstrar separação de responsabilidades

### Padrões GoF (10 min)
- Singleton: conexão única
- Factory: criação de produtos
- Observer: sistema de notificações
- Strategy + Decorator: busca flexível

### Integração (3 min)
- Como os padrões trabalham juntos
- Fluxo completo de uma requisição

### Demonstração (5 min)
- Mostrar sistema funcionando
- Destacar funcionalidades principais

### Conclusão (2 min)
- Benefícios alcançados
- Aprendizados
- Possíveis evoluções

## 📞 Suporte

Se tiver problemas na conversão:
1. Verifique se os arquivos HTML estão na pasta `docs/`
2. Teste abrir no navegador primeiro
3. Use ferramentas online como alternativa
4. Consulte a documentação das ferramentas

---

**Boa apresentação! 🎓**
