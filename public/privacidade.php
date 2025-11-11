<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once '../app/controllers/SecurityController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$security = new SecurityController();
$security->logAction($_SESSION['user_id'], 'ACESSO_PAGINA_PRIVACIDADE');

$page_title = __('nav.privacy') . " - " . __('site.title');
$page_description = __('privacy.description');
require_once '../includes/header.php';
?>

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