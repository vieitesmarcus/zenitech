<?php
require_once __DIR__ . '/../../layout/header.php';
?>
<div class="container">
    <div class="m-3 p-0  d-flex justify-content-end">
        <a href="/users/create" class="btn btn-outline-secondary">Adicionar usuários</a>
    </div>
    <div class="border rounded-3 mt-3 p-2 shadow">
        <?php include __DIR__ . "/../components/search.php"; ?>


        <table class="table">
            <thead class="">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Data de Nascimento</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($paginate['data'])): ?>
                    <caption>Sem dados no momento</caption>
                <?php endif; ?>
                <?php foreach ($paginate['data'] as $user): ?>
                    <tr>
                        <th scope="row"><?= $user->id ?></th>
                        <td>
                            <?php if ($user->foto): ?>
                                <img src="/storage/<?= $user->foto ?? '#' ?>" class="rounded-circle shadow-4"
                                    style="width: 50px; height:50px" alt="Avatar" />
                            <?php endif; ?>

                        </td>
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
                                        Editar
                                    </a>
                                    <?php include __DIR__ . '/../components/modal.php'; ?>

                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?php include __DIR__ . '/../components/pagination.php'; ?>
    </div>

</div>
<?php
require_once __DIR__ . '/../../layout/footer.php';
?>