<template>
	<div class="d-flex flex-column align-items-center px-2 mx-2 cursor-point" @click="$root.toggleOrder($parent.typeId, $parent.userId)" title="Порядок по убыванию/возрастанию">
		<template v-if="$root.orderMode==='DESC'">
			<div>
				<i class="fas fa-angle-up fa-lg"/>
			</div>
			<div>
				<i class="fas fa-angle-down fa-lg mode-off"/>
			</div>
		</template>
		<template v-else-if="$root.orderMode==='ASC'">
			<div>
				<i class="fas fa-angle-up fa-lg mode-off"/>
			</div>
			<div>
				<i class="fas fa-angle-down fa-lg"/>
			</div>
		</template>
	</div>
</template>