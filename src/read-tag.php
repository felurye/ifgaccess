<?php
require_once 'lib/Auth.php';
requireAuth();

$activePage = 'read-tag';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once 'inc/head.html'; ?>
    <title>Consultar : IFGAccess</title>
</head>
<body>
    <?php include_once 'inc/navbar.php'; ?>

    <br>

    <h3 class="text-center" id="blink">Por favor, aproxime a tag/cartão do leitor</h3>

    <p id="currentTag" hidden></p>

    <br>

    <div id="show_user_data">
        <table width="452" border="1" style="border-color: #157347;" class="mx-auto" cellpadding="0" cellspacing="1">
            <tr>
                <td height="40" class="text-center" style="background-color: #157347;">
                    <strong style="color: #fff;">Dados do Usuário</strong>
                </td>
            </tr>
            <tr>
                <td style="background-color: #f9f9f9;">
                    <table width="452" border="0" class="mx-auto" cellpadding="5" cellspacing="0">
                        <tr><td width="113" class="lf">Tag</td><td style="font-weight:bold">:</td><td>--------</td></tr>
                        <tr style="background-color: #f2f2f2;"><td class="lf">Nome</td><td style="font-weight:bold">:</td><td>--------</td></tr>
                        <tr><td class="lf">Matrícula</td><td style="font-weight:bold">:</td><td>--------</td></tr>
                        <tr style="background-color: #f2f2f2;"><td class="lf">E-mail</td><td style="font-weight:bold">:</td><td>--------</td></tr>
                        <tr><td class="lf">Telefone</td><td style="font-weight:bold">:</td><td>--------</td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <script>
        var lastTag = '';

        setInterval(function () {
            $.get('api/last-tag.php', function (data) {
                var tag = data.trim();
                document.getElementById('currentTag').innerHTML = tag;
                if (tag !== '' && tag !== lastTag) {
                    lastTag = tag;
                    showUser(tag);
                }
            });
        }, 500);

        function showUser(tag) {
            if (tag === '') return;
            fetch('api/tag-data.php?tag=' + encodeURIComponent(tag))
                .then(function (r) { return r.text(); })
                .then(function (html) {
                    document.getElementById('show_user_data').innerHTML = html;
                });
        }

        var blink = document.getElementById('blink');
        setInterval(function () {
            blink.style.opacity = (blink.style.opacity == 0 ? 1 : 0);
        }, 750);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
