<template>
	<nav aria-label="Пример навигации по страницам">
		<ul v-if="disabled===false" class="pagination d-flex flex-wrap">
			<li v-if="current==1" class="page-item disabled my-1">
				<span class="page-link" aria-label="Предыдущая">
					<span aria-hidden="true">&laquo;</span>
				</span>
			</li>
			<li v-else class="page-item my-1">
				<span class="page-link" aria-label="Предыдущая" @click="$root.changePagiPage(Number(current) - 1)">
					<span aria-hidden="true">&laquo;</span>
				</span>
			</li>
			<template v-for="index in count">
				<li v-if="index==current" :key="index" class="page-item active-custom my-1" aria-current="page">
					<span class="page-link">
						{{ index }}
					</span>
				</li>
				<li v-else class="page-item cursor-point my-1">
					<span class="page-link" @click="$root.changePagiPage(index)">
						{{ index }}
					</span>
				</li>
			</template>
			<li v-if="current==count" class="page-item my-1 disabled">
				<span class="page-link" aria-label="Следующая">
					<span aria-hidden="true">&raquo;</span>
				</span>
			</li>
			<li v-else class="page-item cursor-point my-1">
				<span class="page-link" aria-label="Следующая" @click="$root.changePagiPage(Number(current) + 1)">
					<a aria-hidden="true">&raquo;</a>
				</span>
			</li>
		</ul>
		<ul v-else class="pagination d-flex flex-wrap">
			<li class="page-item disabled my-1">
				<span class="page-link" aria-label="Предыдущая">
					<span aria-hidden="true">&laquo;</span>
				</span>
			</li>
			<template v-for="index in count">
				<li :key="index" class="page-item my-1 disabled" aria-current="page">
					<span class="page-link">
						{{ index }}
					</span>
				</li>
			</template>
			<li class="page-item my-1 disabled">
				<span class="page-link" aria-label="Следующая">
					<span aria-hidden="true">&raquo;</span>
				</span>
			</li>
		</ul>
	</nav>
</template>
<script>
	export default {
		props: {
			count: Number,
			current: String,
			type: {
				type: String,
				default: 'doc',
			},
			disabled: {
				type: Boolean,
				default: false,
			}
		}
	}
</script>