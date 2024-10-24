<?php

declare(strict_types=1);

namespace Marcus\Zenitech\Helper;

use Marcus\Zenitech\Exceptions\ExceptionUserFoto;
use Throwable;

/**
 * Classe Responsável por fazer o upload de foto no sistema
 */
class UploadFoto
{
    
    const ACCEPT = ['jpg'];
    const BASE_STORAGE = __DIR__ . '/../../public/storage/';

    /**
     * Adiciona uma foto ao sistema após verificar suas extensões, tamanho e dimensões.
     *
     * @param array $upload Um array contendo os detalhes do arquivo enviado, geralmente da superglobal $_FILES.
     * @param string $path O caminho para o diretório onde a imagem será armazenada. O valor padrão é a constante self::BASE_STORAGE.
     *
     * @return string|bool|Throwable O nome único da nova foto em caso de sucesso. Retorna false ou lança uma exceção em caso de erro.
     *
     * @throws ExceptionUserFoto Se a extensão do arquivo não for permitida, tamanho exceder 200KB, dimensões excederem 700x1080px, ou se houver falha no upload.
     *
     * Validações:
     *  - Extensão: Apenas extensões permitidas (definidas em self::ACCEPT) podem ser usadas.
     *  - Tamanho: O arquivo não pode exceder 200KB (200 * 1024 bytes).
     *  - Dimensões: O arquivo não pode ultrapassar 700px de largura ou 1080px de altura.
     *  - Nome Único: Um nome único é gerado para a imagem usando `uniqid()` para evitar sobrescrita de arquivos.
     *  - Salvamento: Se o arquivo temporário não for movido com sucesso para o caminho de destino, uma exceção será lançada.
     */
    public function adicionaFoto(array $upload, string $path = self::BASE_STORAGE): string|bool|Throwable
    {
        $extension = pathinfo($upload['name'], PATHINFO_EXTENSION);
        if (!in_array($extension, self::ACCEPT)) {
            unset($_FILES);

            throw new ExceptionUserFoto('extensão não permitida', 400);
            exit();
        }

        if ($upload['size'] > (200 * 1024)) {
            throw new ExceptionUserFoto('A imagem deve ter no máximo 200kb', 400);
            exit();
        }

        $dimensoes = getimagesize($upload['tmp_name']);

        if (
            $dimensoes[0] > 700 ||
            $dimensoes[1] > 1080
        ) {
            throw new ExceptionUserFoto('A imagem deve ter no máximo 700x1080 pixels.', 400);
            exit();
        }

        $fotoTmp = $upload['tmp_name'];     // armazena o nome temporario da foto
        $novoNomeFoto = uniqid() . ".$extension";               // cria uma novo nome para a foto ser unica e não haver substituição de arquivo
        if (!move_uploaded_file(
            $fotoTmp,
            $path . $novoNomeFoto
        )) {
            throw new ExceptionUserFoto('Não foi possível salvar nesse path', 400);
        }  // move o arquivo temporario com novo nome para a pasta de destino
        return $novoNomeFoto;
    }

    /**
     * Remove uma foto do sistema, se ela existir no caminho especificado.
     *
     * @param string $nomeFoto O nome da foto a ser removida.
     * @param string $path O caminho para o diretório onde a foto está armazenada. O valor padrão é a constante self::BASE_STORAGE.
     *
     * @return bool Retorna true após tentar remover a foto, seja a foto encontrada ou não.
     *
     * Funcionamento:
     *  - Verifica se o arquivo existe no diretório especificado usando `file_exists()`.
     *  - Se o arquivo existir, ele é excluído com `unlink()`.
     *  - Sempre retorna `true`, mesmo que o arquivo não exista.
     */
    public function removeFoto(string $nomeFoto, string $path = self::BASE_STORAGE): bool
    {
        // Verifica se o arquivo existe no caminho especificado
        if (file_exists($path . $nomeFoto)) {
            // Exclui o arquivo do sistema
            unlink($path . $nomeFoto);
        }

        return true;
    }
}
