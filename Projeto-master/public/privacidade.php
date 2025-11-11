<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once '../app/controllers/SecurityController.php';
require_once '../app/controllers/TermoController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$security = new SecurityController();
$security->logAction($_SESSION['user_id'], 'ACESSO_PAGINA_PRIVACIDADE');

// Verificar se usuário já aceitou os termos
$termoController = new TermoController($pdo);
$termoAceito = $termoController->verificarAceite($_SESSION['user_id']);
$mostrarModal = !$termoAceito;

$page_title = __('nav.privacy') . " - " . __('site.title');
$page_description = __('privacy.description');
require_once '../includes/header.php';
?>

<!-- Modal de Aceite de Termos -->
<div id="modalTermos" class="modal-termos" style="display: <?php echo $mostrarModal ? 'flex' : 'none'; ?>;">
    <div class="modal-termos-content">
        <h2 style="color: #5e2b2b; text-align: center; margin-bottom: 1.5rem;">📋 Termos de Uso e Privacidade</h2>
        
        <p style="text-align: center; margin-bottom: 1.5rem; color: #666;">
            Para continuar usando o DressCode, você precisa ler e aceitar nossos termos.
        </p>
        
        <!-- Container dos PDFs -->
        <div class="pdfs-container">
            <div class="pdf-viewer">
                <h3>📄 Termo de Uso</h3>
                <iframe src="assets/pdfs/DRESSCODE - MODELO DE TERMO DE USO DO SISTEMA.docx.pdf" width="100%" height="400px"></iframe>
            </div>
            <div class="pdf-viewer">
                <h3>🔒 Termo de Consentimento</h3>
                <iframe src="assets/pdfs/DRESSCODE - MODELO DE TERMO DE CONSENTIMENTO PARA TRATAMENTO DE DADOS PESSOAIS.docx.pdf" width="100%" height="400px"></iframe>
            </div>
        </div>
        
        <!-- Checkbox de aceite -->
        <div class="aceite-checkbox">
            <label>
                <input type="checkbox" id="checkboxAceite" required>
                <span>Li e aceito o <strong>Termo de Uso</strong> e o <strong>Termo de Privacidade</strong> do DressCode.</span>
            </label>
        </div>
        
        <!-- Campo de assinatura -->
        <div class="assinatura-container">
            <label for="assinaturaNome">✍️ Assinatura Digital (Nome Completo):</label>
            <input type="text" id="assinaturaNome" placeholder="Digite seu nome completo" required>
        </div>
        
        <!-- Botão de aceite -->
        <button id="btnAceitar" class="btn-aceitar" disabled>
            ✅ Concordo e Assino
        </button>
    </div>
</div>

<main style="padding: 2rem 1rem; min-height: 70vh;">
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 style="color: #5e2b2b; margin-bottom: 2rem; text-align: center;">🔒 <?php echo __('privacy.title'); ?></h1>
        
        <!-- Consentimentos LGPD -->
        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <h2 style="color: #5e2b2b; margin-bottom: 1.5rem;">📋 <?php echo __('privacy.consents'); ?></h2>
            
            <div class="consent-item">
                <label>
                    <input type="checkbox" id="consent-coleta" checked disabled>
                    <strong><?php echo __('privacy.basic_data'); ?></strong> (<?php echo __('privacy.required'); ?>)
                </label>
                <p><?php echo __('privacy.basic_data_desc'); ?></p>
            </div>
            
            <div class="consent-item">
                <label>
                    <input type="checkbox" id="consent-marketing" onchange="updateConsent('marketing', this.checked)">
                    <strong><?php echo __('privacy.marketing'); ?></strong>
                </label>
                <p><?php echo __('privacy.marketing_desc'); ?></p>
            </div>
            
            <div class="consent-item">
                <label>
                    <input type="checkbox" id="consent-cookies" onchange="updateConsent('cookies', this.checked)">
                    <strong><?php echo __('privacy.cookies'); ?></strong>
                </label>
                <p><?php echo __('privacy.cookies_desc'); ?></p>
            </div>
            
            <div class="consent-item">
                <label>
                    <input type="checkbox" id="consent-compartilhamento" onchange="updateConsent('compartilhamento', this.checked)">
                    <strong><?php echo __('privacy.sharing'); ?></strong>
                </label>
                <p><?php echo __('privacy.sharing_desc'); ?></p>
            </div>
        </div>
        
        <!-- Seus Direitos -->
        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <h2 style="color: #5e2b2b; margin-bottom: 1.5rem;">⚖️ <?php echo __('privacy.your_rights'); ?></h2>
            
            <div class="rights-grid">
                <div class="right-item">
                    <h3>📥 <?php echo __('privacy.access_data'); ?></h3>
                    <p><?php echo __('privacy.access_data_desc'); ?></p>
                    <button onclick="solicitarDados('acesso')" class="btn-action"><?php echo __('privacy.request_data'); ?></button>
                </div>
                
                <div class="right-item">
                    <h3>✏️ <?php echo __('privacy.correct_data'); ?></h3>
                    <p><?php echo __('privacy.correct_data_desc'); ?></p>
                    <button onclick="solicitarDados('correcao')" class="btn-action"><?php echo __('privacy.request_correction'); ?></button>
                </div>
                
                <div class="right-item">
                    <h3>📤 <?php echo __('privacy.portability'); ?></h3>
                    <p><?php echo __('privacy.portability_desc'); ?></p>
                    <button onclick="exportarDados()" class="btn-action"><?php echo __('privacy.export_data'); ?></button>
                </div>
                
                <div class="right-item">
                    <h3>🗑️ <?php echo __('privacy.delete_account'); ?></h3>
                    <p><?php echo __('privacy.delete_account_desc'); ?></p>
                    <button onclick="excluirConta()" class="btn-danger"><?php echo __('privacy.delete_account'); ?></button>
                </div>
            </div>
        </div>
        
        <!-- Informações de Segurança -->
        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="color: #5e2b2b; margin-bottom: 1.5rem;">🛡️ <?php echo __('privacy.security'); ?></h2>
            
            <div class="security-info">
                <div class="info-item">
                    <strong>🔐 <?php echo __('privacy.encryption'); ?>:</strong> <?php echo __('privacy.encryption_desc'); ?>
                </div>
                <div class="info-item">
                    <strong>📊 <?php echo __('privacy.logs'); ?>:</strong> <?php echo __('privacy.logs_desc'); ?>
                </div>
                <div class="info-item">
                    <strong>🌐 <?php echo __('privacy.ip'); ?>:</strong> <?php echo __('privacy.current_ip'); ?> <code id="user-ip"></code>
                </div>
                <div class="info-item">
                    <strong>⏰ <?php echo __('privacy.last_access'); ?>:</strong> <span id="last-access"></span>
                </div>
            </div>
            
            <div style="margin-top: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                <h4>📞 <?php echo __('privacy.dpo_contact'); ?></h4>
                <p><?php echo __('privacy.privacy_questions'); ?>: <strong>dpo@dresscode.com</strong></p>
                <p><?php echo __('privacy.response_time'); ?></p>
            </div>
        </div>
    </div>
</main>

<style>
/* ========== MODAL DE TERMOS ========== */
.modal-termos {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(5px);
}

.modal-termos-content {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    max-width: 900px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(94, 43, 43, 0.3);
    animation: slideDown 0.4s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.pdfs-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.pdf-viewer {
    border: 2px solid #e1d8d8;
    border-radius: 12px;
    padding: 1rem;
    background: #f8f9fa;
}

.pdf-viewer h3 {
    color: #5e2b2b;
    margin-bottom: 0.75rem;
    font-size: 1rem;
    text-align: center;
}

.pdf-viewer iframe {
    border: none;
    border-radius: 8px;
    background: white;
}

.aceite-checkbox {
    background: #fff8f5;
    padding: 1.25rem;
    border-radius: 12px;
    border: 2px solid #5e2b2b;
    margin-bottom: 1.5rem;
}

.aceite-checkbox label {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    cursor: pointer;
    font-size: 1rem;
}

.aceite-checkbox input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin-top: 2px;
    cursor: pointer;
}

.assinatura-container {
    margin-bottom: 1.5rem;
}

.assinatura-container label {
    display: block;
    color: #5e2b2b;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.assinatura-container input {
    width: 100%;
    padding: 0.875rem;
    border: 2px solid #e1d8d8;
    border-radius: 10px;
    font-size: 1rem;
    font-family: 'Brush Script MT', cursive;
    transition: border-color 0.3s ease;
}

.assinatura-container input:focus {
    outline: none;
    border-color: #5e2b2b;
}

.btn-aceitar {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #5e2b2b 0%, #4a2323 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(94, 43, 43, 0.3);
}

.btn-aceitar:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(94, 43, 43, 0.4);
}

.btn-aceitar:disabled {
    background: #ccc;
    cursor: not-allowed;
    box-shadow: none;
}

@media (max-width: 768px) {
    .modal-termos-content {
        padding: 1.5rem;
        width: 95%;
    }
    
    .pdfs-container {
        grid-template-columns: 1fr;
    }
    
    .pdf-viewer iframe {
        height: 300px;
    }
}

/* ========== ESTILOS ORIGINAIS ========== */
.consent-item {
    margin-bottom: 1.5rem;
    padding: 1rem;
    border: 1px solid #e1d8d8;
    border-radius: 8px;
}

.consent-item label {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    cursor: pointer;
    margin-bottom: 0.5rem;
}

.consent-item input[type="checkbox"] {
    margin-top: 0.25rem;
}

.consent-item p {
    color: #666;
    font-size: 0.9rem;
    margin-left: 1.5rem;
}

.rights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.right-item {
    text-align: center;
    padding: 1.5rem;
    border: 1px solid #e1d8d8;
    border-radius: 8px;
}

.right-item h3 {
    color: #5e2b2b;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.right-item p {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.btn-action, .btn-danger {
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-action {
    background: #5e2b2b;
    color: white;
}

.btn-action:hover {
    background: #4a2323;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.security-info {
    display: grid;
    gap: 1rem;
}

.info-item {
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .rights-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ========== MODAL DE TERMOS ==========
const modal = document.getElementById('modalTermos');
const checkbox = document.getElementById('checkboxAceite');
const btnAceitar = document.getElementById('btnAceitar');
const assinaturaNome = document.getElementById('assinaturaNome');

// Habilitar botão apenas quando checkbox marcado e nome preenchido
function validarFormulario() {
    btnAceitar.disabled = !(checkbox.checked && assinaturaNome.value.trim().length > 0);
}

checkbox.addEventListener('change', validarFormulario);
assinaturaNome.addEventListener('input', validarFormulario);

// Processar aceite
btnAceitar.addEventListener('click', async () => {
    if (!checkbox.checked) {
        Swal.fire('Atenção', 'Você precisa aceitar os termos para continuar.', 'warning');
        return;
    }
    
    if (!assinaturaNome.value.trim()) {
        Swal.fire('Atenção', 'Por favor, digite seu nome completo.', 'warning');
        return;
    }
    
    try {
        const response = await fetch('processar-aceite-termos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                aceito: true,
                assinatura: assinaturaNome.value.trim()
            })
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            modal.style.display = 'none';
            Swal.fire({
                title: 'Sucesso!',
                text: 'Termos aceitos com sucesso!',
                icon: 'success',
                confirmButtonColor: '#5e2b2b'
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            title: 'Erro',
            text: error.message || 'Erro ao processar aceite',
            icon: 'error',
            confirmButtonColor: '#5e2b2b'
        });
    }
});

// ========== SCRIPTS ORIGINAIS ==========

// Mostrar IP do usuário
fetch('https://api.ipify.org?format=json')
    .then(response => response.json())
    .then(data => {
        document.getElementById('user-ip').textContent = data.ip;
    })
    .catch(() => {
        document.getElementById('user-ip').textContent = '<?php echo __('privacy.not_available'); ?>';
    });

// Mostrar último acesso
document.getElementById('last-access').textContent = new Date().toLocaleString('<?php echo getCurrentLang() == 'pt' ? 'pt-BR' : (getCurrentLang() == 'en' ? 'en-US' : (getCurrentLang() == 'es' ? 'es-ES' : 'fr-FR')); ?>');

function updateConsent(tipo, consentimento) {
    fetch('update-consent.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            tipo: tipo,
            consentimento: consentimento
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire('<?php echo __('messages.success'); ?>', '<?php echo __('privacy.consent_updated'); ?>', 'success');
        }
    });
}

function solicitarDados(tipo) {
    Swal.fire({
        title: '<?php echo __('privacy.confirm_request'); ?>',
        text: '<?php echo __('privacy.request_confirmation'); ?>'.replace('{tipo}', tipo),
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<?php echo __('privacy.yes_request'); ?>',
        cancelButtonText: '<?php echo __('privacy.cancel'); ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('data-request.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    tipo: tipo
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire('<?php echo __('privacy.requested'); ?>!', data.message, 'success');
            });
        }
    });
}

function exportarDados() {
    Swal.fire('Processando...', 'Preparando seus dados para download.', 'info');
    
    fetch('export-data.php', {
        method: 'POST'
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'meus-dados-dresscode.json';
        a.click();
        window.URL.revokeObjectURL(url);
        
        Swal.fire('Sucesso!', 'Seus dados foram baixados.', 'success');
    });
}

function excluirConta() {
    Swal.fire({
        title: '⚠️ ATENÇÃO',
        text: 'Esta ação é IRREVERSÍVEL. Todos os seus dados serão excluídos permanentemente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir tudo',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Digite "EXCLUIR" para confirmar',
                input: 'text',
                inputPlaceholder: 'Digite EXCLUIR',
                showCancelButton: true,
                confirmButtonText: 'Confirmar Exclusão',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                preConfirm: (value) => {
                    if (value !== 'EXCLUIR') {
                        Swal.showValidationMessage('Digite exatamente "EXCLUIR"');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('delete-account.php', {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Conta Excluída', 'Seus dados foram excluídos permanentemente.', 'success').then(() => {
                                window.location.href = 'index.php';
                            });
                        }
                    });
                }
            });
        }
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>