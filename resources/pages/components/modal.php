<!-- Button trigger modal -->
<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $user->id ?>">
    excluir
</button>

<!-- Modal -->
 <!-- Confirmação do usuário para realizar exclusão -->
<div class="modal fade" id="exampleModal<?= $user->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> <?= $user->nome ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                tem certeza que quer deletar esse usuário?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">cancelar</button>
                <form action="users/destroy" method="POST">
                    <input type="hidden" name="id" value="<?= $user->id ?>">
                    <button type="submit" class="btn btn-danger">Deletar</button>
                </form>
            </div>
        </div>
    </div>
</div>