<?php 
    $paginacao = $this->request->getParam('paging')[$this->request->getParam('modelClass')];
?>
<table class='table table-striped table-bordered table-sm'>
    <thead>
        <tr>
            <?php foreach($config['fields'] as $_l2 => $_field) : 
                $field  = $_field;
                $th     = isset($config['schema'][$field]['th']) ? $config['schema'][$field]['th'] : null;
                $title  = isset($config['schema'][$field]['title']) ? $config['schema'][$field]['title'] : $field;
                $title  = isset($this->request->getParam('aliasField')[$field]) ? $this->request->getParam('aliasField')[$field] : $title;
                $thHtml = $title;
                if ( isset($config['schema'][$field]['sort']) )
                {
                    $thHtml = $this->Paginator->sort($field, $title);
                }
            ?>

            <th <?php if ( isset($th) ) { foreach($th as $_tag => $_vlr) { echo "$_tag='$_vlr' "; }} ?>>
                <?= $thHtml; ?>
            </th>

            <?php endforeach; ?>
        </tr>
    </thead>

    <tbody>
        <?php foreach($this->request->data as $_l => $_Entity) : ?>
            <tr>
                <?php 
                    foreach($config['fields'] as $_l2 => $_field) :
                    $td     = isset($config['schema'][$_field]['td']) ? $config['schema'][$_field]['td'] : null;
                    $field  = $_field;
                ?>

                <td <?php if ( isset($td) ) { foreach($td as $_tag => $_vlr) { echo "$_tag='$_vlr' "; }} ?>>
                    <?= $_Entity->$field; ?>
                </td>

                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class='row border-top py-1'>
    <div class='col-9'>
        <?php if ( $paginacao ) : ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination small">
                <?= $this->Paginator->numbers(['templates'=>'Bootstrap.paginator-template']);?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <div class='col-3 text-right font-italic pt-2'>
        exibindo <?= @$paginacao['current'] ?> de <?= @$this->Number->format($paginacao['count']) ?>
    </div>
</div>