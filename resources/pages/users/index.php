<?php
require_once __DIR__ . '/../../layout/header.php';
?>
<div class="container">

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
                <th scope="col">Data de Nascimento</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <th scope="row"><?= $user->id ?></th>
                    <td><?= $user->nome ?></td>
                    <td><?= $user->email ?></td>
                    <td><?= date_format(new DateTime($user->data_nascimento), 'd/m/Y') ?></td>
                    <td>
                        <p class="d-inline-flex gap-1">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample<?= $user->id ?>" aria-expanded="false" aria-controls="collapseExample">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                </svg>
                            </button>
                        </p>
                        <div class="collapse" id="collapseExample<?= $user->id ?>">
                            <div class="d-flex gap-1">
                                <a href="users/edit?id=<?= $user->id ?>" class="btn btn-warning">
                                    Edit
                                </a>
                                <form action="users/delete" method="POST">
                                    <input type="hidden" name="id" value="<?= $user->id ?>">
                                    <button type="submit" class="btn btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

</div>
<?php
require_once __DIR__ . '/../../layout/footer.php';
?>