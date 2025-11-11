<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once '../app/controllers/AuthController.php';

$auth = new AuthController();
$result = $auth->register();

$page_title = __('auth.register') . " - DressCode";
$page_description = __('site.description');
?>

<!DOCTYPE html>
<html lang="<?php echo getCurrentLang(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $page_description; ?>">
    <title><?php echo $page_title; ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f2ef 0%, #e8e0db 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(94, 43, 43, 0.1);
            margin: 0 auto;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: #5e2b2b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        h1 {
            color: #5e2b2b;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            text-align: center;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #5e2b2b;
            font-weight: 500;
        }

        input[type="text"], input[type="email"], input[type="password"], input[type="tel"], input[type="file"] {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid #e1d8d8;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #5e2b2b;
        }

        .checkbox-group {
            display: flex;
            gap: 2rem;
            margin: 1.5rem 0;
            padding: 1rem;
            background: #f8f6f4;
            border-radius: 8px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-item input[type="radio"] {
            width: auto;
            margin: 0;
        }

        .conditional-fields {
            display: none;
            background: #f0f8ff;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid #5e2b2b;
        }

        .conditional-fields.show {
            display: block;
        }

        .endereco-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .endereco-grid-3 {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 1rem;
        }

        .photo-upload {
            text-align: center;
            padding: 1rem;
            border: 2px dashed #e1d8d8;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .photo-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1rem;
            display: none;
        }

        .btn {
            width: 100%;
            background: #5e2b2b;
            color: white;
            border: none;
            padding: 0.875rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            margin-top: 1rem;
        }

        .btn:hover {
            background: #4a2323;
        }

        .links {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e1d8d8;
            text-align: center;
        }

        .links a {
            color: #5e2b2b;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .checkbox-group {
                flex-direction: column;
                gap: 1rem;
            }
            
            .endereco-grid, .endereco-grid-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">DC</div>
        <h1><?php echo __('auth.create_account_title'); ?></h1>

        <form action="cadastro.php" method="POST" enctype="multipart/form-data" id="formCadastro">
            <!-- Campos básicos -->
            <div class="form-row">
                <div class="form-group">
                    <label for="nome"><?php echo __('auth.name'); ?> *</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="sobrenome"><?php echo __('auth.surname'); ?> *</label>
                    <input type="text" id="sobrenome" name="sobrenome" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="usuario"><?php echo __('auth.username'); ?> *</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>
                <div class="form-group">
                    <label for="celular"><?php echo __('auth.phone'); ?> *</label>
                    <input type="tel" id="celular" name="celular" required placeholder="(11) 99999-9999">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email"><?php echo __('auth.email'); ?> *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha"><?php echo __('auth.password'); ?> *</label>
                    <input type="password" id="senha" name="senha" required minlength="6">
                </div>
            </div>

            <!-- Foto de perfil -->
            <div class="form-group">
                <label>Foto de Perfil/Banner</label>
                <div class="photo-upload">
                    <img id="photoPreview" class="photo-preview" alt="Preview">
                    <div id="uploadText">
                        <p>📷 Clique para adicionar uma foto</p>
                        <small>JPG, PNG até 5MB</small>
                    </div>
                    <input type="file" id="foto" name="foto" accept="image/*" style="display: none;">
                </div>
            </div>

            <!-- Tipo de usuário -->
            <div class="checkbox-group">
                <h3 style="color: #5e2b2b; margin-bottom: 1rem; width: 100%;">Tipo de Conta *</h3>
                <div class="checkbox-item">
                    <input type="radio" id="tipo_comprador" name="tipo_usuario" value="comprador" required>
                    <label for="tipo_comprador">🛒 Comprador (Usuário)</label>
                </div>
                <div class="checkbox-item">
                    <input type="radio" id="tipo_vendedor" name="tipo_usuario" value="vendedor" required>
                    <label for="tipo_vendedor">🛍️ Vendedor (Brechó)</label>
                </div>
            </div>

            <!-- Campos condicionais para vendedor -->
            <div id="campos_vendedor" class="conditional-fields">
                <h3 style="color: #5e2b2b; margin-bottom: 1rem;">📍 Dados do Brechó</h3>
                <div class="form-group">
                    <label for="nome_brecho">Nome do Brechó *</label>
                    <input type="text" id="nome_brecho" name="nome_brecho">
                </div>
                <div class="form-group">
                    <label for="localizacao_brecho">Localização do Brechó *</label>
                    <input type="text" id="localizacao_brecho" name="localizacao_brecho" placeholder="Cidade, Estado">
                </div>
            </div>

            <!-- Campos condicionais para comprador -->
            <div id="campos_comprador" class="conditional-fields">
                <h3 style="color: #5e2b2b; margin-bottom: 1rem;">🏠 Endereço de Entrega</h3>
                <div class="endereco-grid">
                    <div class="form-group">
                        <label for="cep">CEP *</label>
                        <input type="text" id="cep" name="cep" placeholder="00000-000">
                    </div>
                    <div class="form-group">
                        <label for="rua">Rua *</label>
                        <input type="text" id="rua" name="rua">
                    </div>
                </div>
                <div class="endereco-grid-3">
                    <div class="form-group">
                        <label for="bairro">Bairro *</label>
                        <input type="text" id="bairro" name="bairro">
                    </div>
                    <div class="form-group">
                        <label for="numero">Número *</label>
                        <input type="text" id="numero" name="numero">
                    </div>
                    <div class="form-group">
                        <label for="complemento">Complemento</label>
                        <input type="text" id="complemento" name="complemento">
                    </div>
                </div>
                <div class="endereco-grid">
                    <div class="form-group">
                        <label for="cidade">Cidade *</label>
                        <input type="text" id="cidade" name="cidade">
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado *</label>
                        <input type="text" id="estado" name="estado" placeholder="SP">
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn" id="btnCadastrar"><?php echo __('auth.create_account'); ?></button>
        </form>

        <div class="links">
            <a href="login.php"><?php echo __('auth.already_have_account'); ?></a>
        </div>
    </div>

    <script>
        // Mostrar/ocultar campos condicionais baseado no tipo de usuário
        document.querySelectorAll('input[name="tipo_usuario"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const camposVendedor = document.getElementById('campos_vendedor');
                const camposComprador = document.getElementById('campos_comprador');
                const inputsVendedor = camposVendedor.querySelectorAll('input');
                const inputsComprador = camposComprador.querySelectorAll('input:not([name="complemento"])');
                
                // Limpar todos os campos primeiro
                camposVendedor.classList.remove('show');
                camposComprador.classList.remove('show');
                inputsVendedor.forEach(input => {
                    input.required = false;
                    input.value = '';
                });
                inputsComprador.forEach(input => {
                    input.required = false;
                    input.value = '';
                });
                
                // Mostrar campos baseado na seleção
                if (this.value === 'vendedor') {
                    camposVendedor.classList.add('show');
                    inputsVendedor.forEach(input => input.required = true);
                } else if (this.value === 'comprador') {
                    camposComprador.classList.add('show');
                    inputsComprador.forEach(input => input.required = true);
                }
            });
        });

        // Upload de foto
        document.querySelector('.photo-upload').addEventListener('click', function() {
            document.getElementById('foto').click();
        });

        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photoPreview');
                    const uploadText = document.getElementById('uploadText');
                    
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    uploadText.innerHTML = '<p>✅ Foto selecionada</p><small>Clique para alterar</small>';
                };
                reader.readAsDataURL(file);
            }
        });

        // Máscara para celular
        document.getElementById('celular').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });

        // Máscara para CEP
        document.getElementById('cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });

        // Buscar endereço por CEP
        document.getElementById('cep').addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('rua').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('estado').value = data.uf;
                        }
                    })
                    .catch(error => console.log('Erro ao buscar CEP'));
            }
        });
    </script>

    <?php if ($result): ?>
        <script>
            const tipo = '<?= $result["status"] ?>';
            const titulo = tipo === 'success' ? '✅ Sucesso!' : '❌ Erro!';
            const texto = <?= json_encode($result["message"]) ?>;

            Swal.fire({
                title: titulo,
                text: texto,
                icon: tipo === 'success' ? 'success' : 'error',
                confirmButtonColor: '#5e2b2b',
                timer: 3500,
                timerProgressBar: true,
                showConfirmButton: false
            });

            if (tipo === "success") {
                setTimeout(() => {
                    window.location.href = "login.php";
                }, 4000);
            }
        </script>
    <?php endif; ?>
</body>
</html>