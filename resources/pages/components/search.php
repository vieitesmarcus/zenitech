    <div class="row justify-content-end">
        <div class="col-md-12">
            <form action="/users" method="GET">
                <div class="input-group mb-3 ">
                    <input type="text" class="form-control" placeholder="Nome ou e-mail" aria-label="Nome ou Email" aria-describedby="button-addon2" name="pesquisa" value="<?= $_GET['pesquisa'] ?? '' ?>">
                    <button class="btn btn-outline-success" type="submit" id="button-addon2">Pesquisar</button>
                    <a class="btn btn-outline-danger" href="/users" id="button-addon2">Limpar</a>
                </div>
                <div class="col-md-6 ">
                    <div class="input-group mb-3 ">
                        <select class="form-select" aria-label="Default select example" name="limite">
                            <option <?= !isset($_GET['limite']) ? 'selected' : '' ?> value="5">Limite de itens</option>
                            <option value="5" <?= isset($_GET['limite']) && $_GET['limite'] == 5  ? 'selected' : '' ?>>5</option>
                            <option value="10" <?= isset($_GET['limite']) && $_GET['limite'] == 10  ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= isset($_GET['limite']) && $_GET['limite'] == 25  ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= isset($_GET['limite']) && $_GET['limite'] == 50  ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= isset($_GET['limite']) && $_GET['limite'] == 100  ? 'selected' : '' ?>>100</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>