<template>
	<div class="d-flex flex-column mb-2">
        <vue-multiselect @input="getFull()" v-model="$parent.depValue" :options="list" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск подразделений" track-by="id" :preselect-first="false" :max="5" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано" label="title">
<!-- 			<template slot="selection" slot-scope="{ values, search, isOpen }">
				<span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">ПОдразделения выбрано:&nbsp;{{ values.length }} </span>
			</template>
			<template slot="noOptions">Список пуст.</template>
			<template slot="noResult">Ничего не найдено...</template> -->
			<template slot="noOptions" slot-scope="props">
				Список пуст.
			</template>
			<template slot="noResult" slot-scope="props">
				Ничего не найдено...
			</template>
		</vue-multiselect>
        <template v-for="(item, index) in $parent.depValue">
        	<div v-if="item.headId!=null" class="font-bold m-2">
        		<i class="fas fa-check-circle"/>&nbsp;&nbsp;{{ item.title }}
				<template v-for="n in list">
					<span v-if="item.headId==n.id" class="greytxt">
						&nbsp;({{ n.title }})
					</span>
				</template>
			</div>
			<div v-else class="font-bold m-2">
				<i class="fas fa-check-circle"/>&nbsp;&nbsp;{{ item.title }}
			</div>
        </template>
	</div>
</template>
<script>
	export default {
		props: {
			list: Array,
			deps: Array,
			getDeps: Function,
		},
		data() {
			return {
				// 
			}
		},
		created() {
			if (this.deps) {
				this.$parent.depValue = this.deps;
			};
			// console.log(this.deps);
		},
		methods: {
			getFull: function() {
				this.getDeps({
					data: this.$parent.depValue,
				});
				// console.log(data);
			},
		}
	}
</script>