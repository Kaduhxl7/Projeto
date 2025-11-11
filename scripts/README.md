# 🚀 Scripts de Execução

Esta pasta contém os scripts para executar o projeto.

## 📁 Scripts Disponíveis

- `INSTALAR.bat` - **Instalação automática completa**
- `start-server-windows.bat` - Iniciar servidor (Windows)
- `start-server.bat` - Iniciar servidor (alternativo)

## 🎯 Como usar

### Instalação completa (recomendado):
```bash
# Execute este script primeiro
INSTALAR.bat
```

### Apenas iniciar servidor:
```bash
# Se já estiver instalado
start-server-windows.bat
```

## ⚙️ O que cada script faz

### INSTALAR.bat
- Verifica PHP
- Configura banco de dados
- Inicia servidor automaticamente

### start-server-windows.bat
- Inicia servidor PHP na porta 8000
- Acesso: http://localhost:8000

## 🔧 Requisitos
- Laragon ou XAMPP instalado
- PHP 8.0+ e MySQL rodando