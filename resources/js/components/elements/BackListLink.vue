<template>
	<div>
		<a href="/documents?type=2&page=1">
			<i class="fas fa-long-arrow-alt-left fa-lg"/>&nbsp;&nbsp;<span class="font-bold">К списку документов</span>
		</a>
		<div class="mt-3">
			<template v-for="item in links">
				<a v-if="typeId==item.typeId" :href="'/'+ item.link +'?type=2&page=1'">
					<i class="fas fa-long-arrow-alt-left fa-lg"/>&nbsp;&nbsp;<span class="font-bold">К списку&nbsp;{{ item.title }}</span>
				</a>
			</template>
		</div>
	</div>
</template>
<script type="text/javascript">
	export default {
		props: {
			typeId: {
				default: 1,
				type: Number,
			}
		},
		data() {
			return {
				links: [
					{
						typeId: 1,
						link: 'memos',
						title: 'служебных записок',
					},
					{
						typeId: 2,
						link: 'contracts',
						title: 'договоров',
					},
					{
						typeId: 4,
						link: 'otherdocs',
						title: 'прочих документов',
					},
					{
						typeId: 6,
						link: 'letters',
						title: 'входящих писем',
					},
					{
						typeId: 7,
						link: 'outletters',
						title: 'исходящих писем',
					},
					{
						typeId: 9,
						link: 'ordersod',
						title: 'приказов по ОД',
					},
					{
						typeId: 12,
						link: 'notifications',
						title: 'уведомлений',
					},
					{
						typeId: 13,
						link: 'ordocs',
						title: 'организационно-распорядительных документов',
					},
					{
						typeId: 14,
						link: 'additionalagreements',
						title: 'дополнительных соглашений',
					},
					{
						typeId: 15,
						link: 'socs',
						title: 'соглашений',
					},
				]
			}
		},
	}
</script>