<template>
	<tr v-if="data.order==null">
		<td>
			<!-- {{ $root.makeFio(data.surname, data.firstname, data.patronymic) }} -->
			<vue-link-userfio :data="data.user" :short="true"/>
		</td>
		<td class="ta-center">
			<vue-elem-timestamp :date-time="data.created_at"/>
		</td>
		<td class="ta-center" v-if="data.note!=null">
			<vue-td-formattedtitle :text="data.note" :length="40"/>
		</td>
		<td class="ta-center" v-else>
			...
		</td>				
		<td class="ta-center timestamp_font" v-if="data.updated_at!=null">
			<vue-elem-timestamp :date-time="data.updated_at"/>
		</td>
		<td class="ta-center" v-else>
			...
		</td>
		<template v-if="main.refused_at!=null&&data.updated_at==null">
			<td class="ta-right p-1">
				<span class="font-bold">Отклонено</span>&nbsp;<br/>(автоматически)
			</td>
		</template>
		<template v-else>
			<td v-if="data.approved_at!=null" class="status_approved font-bold ta-right">
				<template v-if="data.length==1">
					Подписано
				</template>
				<template v-else>
					Согласовано
				</template>
			</td>
			<td v-else-if="data.refused_at!=null" class="status_refused font-bold ta-right">
				Отклонено
			</td>
			<td v-else class="status_considering font-bold ta-right">
				На согласовании
			</td>
		</template>
	</tr>
	<tr v-else>
		<td>
			<vue-link-userfio :data="data.user" :short="true"/>
		</td>
		<td class="ta-center timestamp_font" v-if="data.created_at!=null">
			<vue-elem-timestamp :date-time="data.created_at"/>
		</td>
		<td class="ta-center" v-else>
			...
		</td>
		<td class="ta-center" v-if="data.note!=null">
			<vue-td-formattedtitle :text="data.note" :length="50"/>
		</td>
		<td class="ta-center" v-else>
			...
		</td>								
		<td class="ta-center timestamp_font" v-if="data.updated_at!=null">
			<vue-elem-timestamp :date-time="data.updated_at"/>
		</td>
		<td class="ta-center" v-else>
			...
		</td>
		<template v-if="data.updated_at==null&&main.refused_at!=null">
			<td class="ta-right p-1">
				<span class="font-bold">Отклонено</span>&nbsp;<br/>(автоматически)
			</td>
		</template>
		<template v-else>
			<td v-if="data.approved_at!=null" class="status_approved font-bold ta-right" title="Последовательное согласование">
				<template v-if="data.length==1">
					Подписано
				</template>
				<template v-else>
					Согласовано&nbsp;&nbsp;
				</template>
				<i class="fas fa-level-down-alt fa-lg"/>
			</td>
			<td v-else-if="data.refused_at!=null" class="status_refused font-bold ta-right">
				Отклонено
			</td>
			<td v-else class="status_considering font-bold ta-right" title="Последовательное согласование">
				На согласовании
			</td>
		</template>
	</tr>
</template>
<script>
	export default {
		props: {
			data: Object,
			main: Object,
		},
	}
</script>