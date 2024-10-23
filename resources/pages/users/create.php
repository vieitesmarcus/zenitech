<?php
require_once __DIR__ . '/../../layout/header.php';
?>
<div class="container">
    <div class="m-3 p-0 d-flex justify-content-end">
        <a href="/users" class="btn btn-outline-secondary">Listar usuários</a>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">


            <form action="/users/store" method="POST" enctype="multipart/form-data" class="w">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?= isset($_SESSION['nome']) ? $_SESSION['nome'] : ''?>" maxlength="255" minlength="3" required>
                    <?php if (isset($_SESSION['errors']['nome'])): ?>
                        <div id="" class="text-danger">
                            <?= $_SESSION['errors']['nome'] ?>
                        </div>
                    <?php endif; ?>

                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : ''?>" required>
                    <?php if (isset($_SESSION['errors']['email'])): ?>
                        <div id="" class="text-danger">
                            <?= $_SESSION['errors']['email'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="data_nascimento" class="form-label">Data de nascimento</label>
                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?= isset($_SESSION['data_nascimento']) ? $_SESSION['data_nascimento'] : ''?>" required>
                    <?php if (isset($_SESSION['errors']['data_nascimento'])): ?>
                        <div id="" class="text-danger">
                            <?= $_SESSION['errors']['data_nascimento'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" class="form-control" id="foto" name="foto">
                </div>

                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../../layout/footer.php';
?>