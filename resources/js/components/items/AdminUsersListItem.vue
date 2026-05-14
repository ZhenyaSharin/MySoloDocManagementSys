<template>
	<tr class="cursor-point" data-toggle="modal" data-target="#userprofile_modal" data-info="">
		<th scope="row">{{ data.id }}</th>
		<td class="ta-center" v-if="data.surname==null&&data.firstname==null&&data.patronymic==null">
			{{ data.login }}
		</td>
		<td class="ta-center" v-else>
			{{ data.surname }}&nbsp;{{ data.firstname }}&nbsp;{{ data.patronymic }}
		</td>
		<td class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.created_at"/>
		</td>
		<td class="ta-center timestamp_font" v-if="data.updated_at!=null">
			<vue-elem-timestamp :date-time="data.updated_at"/>
		</td>
		<td class="ta-center" v-else>
			...
		</td>
		<td class="ta-center" v-if="data.department.length!=0">
			<template v-for="item in data.department">
				<span>
					{{ item.title }}
				</span><br/>
			</template>
		</td>
		<td class="ta-center" v-else>
			Не указано
		</td>
		<template v-if="data.role==false">
			<td class="ta-center">
				Пользователь (огр.)
			</td>
		</template>
		<template v-else>
			<td class="ta-center">
				{{ data.role.title }}
			</td>
		</template>
		<td v-if="data.removed==null" class="td-text-green ta-right">
			Активен
		</td>
		<td v-else class="status_refused ta-right">
			Заблокирован
		</td>
	</tr>
</template>

<script>
	export default {
		props: {
			data: Object
		},
	}
</script>