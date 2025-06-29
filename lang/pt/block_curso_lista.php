<?php
/**
 * Strings de idioma en portugués para o plugin Curso Lista
 * 
 * @package    block_curso_lista
 * @author     Harvinvapi
 * @copyright  2024 Harvinvapi
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link       https://www.instagram.com/Harvinvapi
 * @link       https://www.linkedin.com/in/harvinvapi
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Meus Cursos';
$string['curso_lista'] = 'Curso Lista';
$string['curso_lista:addinstance'] = 'Adicionar um novo bloco Meus Cursos';
$string['curso_lista:myaddinstance'] = 'Adicionar um novo bloco Meus Cursos ao Meu Moodle';

// Configuração básica
$string['blocksettings'] = 'Configurações do bloco';
$string['config_title'] = 'Título personalizado do bloco';
$string['config_body'] = 'Descrição personalizada';

// Configuração de títulos dos cursos
$string['title_color'] = 'Cor do título do curso';
$string['title_color_help'] = 'Cor que será aplicada aos títulos dos cursos. Use formato hexadecimal (#000000) para melhores resultados.';
$string['title_size'] = 'Tamanho do título';
$string['title_size_help'] = 'Tamanho da fonte para os títulos dos cursos.';
$string['title_weight'] = 'Peso do título';
$string['title_weight_help'] = 'Espessura da fonte para os títulos dos cursos.';

// Configuração de botões
$string['buttoncolor'] = 'Cor do botão';
$string['buttoncolordesc'] = 'Selecione a cor para os botões "Ir para o curso". Use formato hexadecimal (#FF0000) ou nomes de cores CSS.';
$string['gradient_start'] = 'Cor inicial do gradiente';
$string['gradient_start_desc'] = 'Cor inicial para o gradiente do botão (formato hexadecimal: #FF0000)';
$string['gradient_end'] = 'Cor final do gradiente';
$string['gradient_end_desc'] = 'Cor final para o gradiente do botão (formato hexadecimal: #00FF00)';

// Conteúdo
$string['nocourses'] = 'Você não tem cursos disponíveis no momento.';
$string['loginrequired'] = 'Você deve fazer login para ver seus cursos.';
$string['error_loading_courses'] = 'Erro ao carregar os cursos. Por favor, tente novamente.';
$string['courseimage'] = 'Imagem do curso';
$string['button_text'] = 'Ir para o curso';
$string['progress'] = 'Progresso';
$string['progress_aria'] = 'Progresso do curso: {$a} por cento concluído';

// Validação
$string['invalid_color'] = 'A cor inserida não é válida. A cor padrão foi aplicada.';
$string['invalid_title_color'] = 'A cor do título não é válida. A cor padrão foi aplicada.';

// Acessibilidade
$string['course_link_aria'] = 'Ir para o curso: {$a}';
$string['progress_ring_aria'] = 'Progresso do curso';

// Configuração de ajuda
$string['buttoncolor_help'] = 'Personalize a cor dos botões do bloco. Exemplos de formatos válidos:
<ul>
<li>Hexadecimal: #3498db, #FF5733</li>
<li>Hexadecimal curto: #fff, #000</li>
<li>Nomes CSS: red, blue, green, purple, orange</li>
</ul>';

$string['gradient_help'] = 'Configure um gradiente personalizado para os botões. As cores devem estar em formato hexadecimal (#FF0000).';

// Donação e redes sociais
$string['donation_title'] = '☕ Me convide para um café';
$string['donation_description'] = 'Se este plugin foi útil para você, considere fazer uma pequena doação para apoiar seu desenvolvimento e manutenção.';
$string['donate_paypal'] = '💳 Doar com PayPal';
$string['follow_social'] = 'Siga-me nas redes sociais:';
$string['website'] = '🌐 Educacion.lat';
$string['instagram'] = '📷 Instagram';
$string['linkedin'] = '💼 LinkedIn';
$string['developed_by'] = 'Desenvolvido com ❤️ por <strong>Harvin Vapi</strong>';