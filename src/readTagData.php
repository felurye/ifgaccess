<?php
require_once 'func/auth.php';
require_once 'func/database.php';
requireAuth();

$tag = isset($_GET['tag']) ? trim($_GET['tag']) : '';

$data = [];
$msg  = null;

if ($tag !== '') {
    $rows = Database::query('SELECT * FROM users WHERE tag = ?', [$tag]);
    $data = $rows[0] ?? [];
}

if (empty($data)) {
    $msg              = 'A tag aproximada não está cadastrada!';
    $data['tag']      = $tag;
    $data['name']     = '--------';
    $data['enrollment'] = '--------';
    $data['email']    = '--------';
    $data['phone']    = '--------';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once 'inc/head.html'; ?>
</head>
<body>
    <table width="452" border="1" style="border-color: #157347;" class="mx-auto" cellpadding="0" cellspacing="1" style="padding: 2px">
        <tr>
            <td height="40" class="text-center" style="background-color: #157347;">
                <strong style="color: #fff;">Dados do Usuário</strong>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f9f9f9;">
                <table width="452" border="0" class="mx-auto" cellpadding="5" cellspacing="0">
                    <tr><td width="113" class="lf">Tag</td><td style="font-weight:bold">:</td><td><?php echo e($data['tag']); ?></td></tr>
                    <tr style="background-color: #f2f2f2;"><td class="lf">Nome</td><td style="font-weight:bold">:</td><td><?php echo e($data['name']); ?></td></tr>
                    <tr><td class="lf">Matrícula</td><td style="font-weight:bold">:</td><td><?php echo e($data['enrollment']); ?></td></tr>
                    <tr style="background-color: #f2f2f2;"><td class="lf">E-mail</td><td style="font-weight:bold">:</td><td><?php echo e($data['email']); ?></td></tr>
                    <tr><td class="lf">Telefone</td><td style="font-weight:bold">:</td><td><?php echo e($data['phone']); ?></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <?php if ($msg): ?>
        <p class="text-center mt-2" style="color: red;"><?php echo e($msg); ?></p>
    <?php endif; ?>
</body>
</html>
