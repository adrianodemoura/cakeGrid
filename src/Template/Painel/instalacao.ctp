<?php

	echo $this->Html->script( ['painel/instalacao'],	['block'=>true] );
	echo $this->Html->css( ['painel/instalacao'], 		['block'=>true] );

	$optionsNome 	= ['required'=>'required', 'label'=>false, 'autocomplete'=>'off', 'placeholder'=>'nome','name'=>'nome', 'id'=>'inNome', 'class'=>'form-control', 'autofocus'=>true, 'default'=>'Administrador Aplicação'];
	$optionsEmail 	= ['required'=>'required', 'label'=>false, 'autocomplete'=>'off', 'placeholder'=>'e-mail','name'=>'email', 'id'=>'inEmail', 'class'=>'form-control', 'default'=>'admin@admin.com.br'];
	$optionsSenha 	= ['required'=>'required', 'label'=>false, 'autocomplete'=>'off', 'placeholder'=>'senha', 'name'=>'senha', 'id'=>'inSenha', 'class'=>'form-control', 'type'=>'password', 'default'=>''];
	$optionsEnviar	= ['name'=>'inEnviar', 'id'=>'btnEnviar', 'div'=>null, 'type'=>'submit', 'class'=>'btn btn-secondary btn-aguarde'];
	$optionsFechar 	= ['name'=>'inFechar', 'id'=>'btnFechar', 'div'=>null, 'type'=>'button', 'class'=>'btn btn-secondary btn-aguarde ml-3', 'label'=>false];

?>

<div class="mh-100" style="height: 500px;">

	<div class="h-25 d-inline-block"></div>

	<div class="row">
		<div class="col-4"></div>

		<div class="col-4 rounded-lg bordered bg-info py-2 px-5">
		<?= $this->Form->create($LoginForm, ['class'=>'form']); ?>
			
			<div class="mt-2">
				<?= $this->Form->control('nome', $optionsNome); ?>
			</div>

			<div class="mt-2">
				<?= $this->Form->control('email', $optionsEmail); ?>
			</div>

			<div class="mt-2">
				<?= $this->Form->control('senha', $optionsSenha); ?>
			</div>

			<div class="mt-3">
				<div class="row">
					<?= $this->Form->control('Enviar', $optionsEnviar); ?>
				</div>
			</div>
		<?= $this->Form->end(); ?>
		</div>

		<div class="col-2"></div>
</div>

</div>