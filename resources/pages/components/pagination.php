<?php
//PAGINATION FEITO! UFA!
?>
<?php $paginaAtual = $_GET['page'] ?? 1; ?>
<div class="d-flex justify-content-between">
    <nav aria-label="..." class="">
        <ul class="pagination">

            <li class="page-item <?= $paginaAtual == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="/users?page=<?= $paginaAtual < 1 ? 1 : $paginaAtual - 1 ?><?= isset($_GET['pesquisa']) ? '&pesquisa=' . $_GET['pesquisa'] : '' ?><?= isset($_GET['limite']) ? '&limite=' . $_GET['limite'] : '' ?>">Previous</a>
            </li>
            <?php $intervaloDePaginas = $paginaAtual >= $totalPaginas ? $totalPaginas : $paginaAtual ?>
            <?php for ($i = $paginaAtual - 2; $i < $intervaloDePaginas + 3; $i++): ?>

                <?php if ($i < 1 || $i > $totalPaginas) continue; ?>
                <li class="page-item <?= $i == $paginaAtual ? 'active' : '' ?>"><a class="page-link" href="/users?page=<?= $i ?><?= isset($_GET['pesquisa']) ? '&pesquisa=' . $_GET['pesquisa'] : '' ?><?= isset($_GET['limite']) ? '&limite=' . $_GET['limite'] : '' ?> "><?= $i ?></a></li>
            <?php endfor; ?>

            <li class="page-item">
                <a class="page-link <?= ($paginaAtual == $totalPaginas) ||  $totalPaginas == 0 || $paginaAtual > $totalPaginas  ? 'disabled' : '' ?>" href="/users?page=<?= $paginaAtual < 1 ? 1 : $paginaAtual + 1 ?><?= isset($_GET['pesquisa']) ? '&pesquisa=' . $_GET['pesquisa'] : '' ?><?= isset($_GET['limite']) ? '&limite=' . $_GET['limite'] : '' ?>">Next</a>
            </li>
        </ul>
    </nav>
    <div class="">
        <strong>
            Total : <?= $paginate['users_count'] ?>
        </strong>
    </div>
</div>