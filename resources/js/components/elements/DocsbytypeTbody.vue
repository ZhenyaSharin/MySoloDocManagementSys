<template>
	<tbody v-if="$root.sortArr!=null">
		<template v-if="$root.sortArr.length>0">
			<vue-item-letterlist v-for="(item, index) in $root.sortArr" :data="item" :date-mode="$root.dateMode"  :index="item.id" :key="item.id"/>
		</template>
		<template v-else>
			<vue-tr-notfound/>
		</template>
	</tbody>
	<tbody v-else>
		<template v-if="$root.docsListAll.length>0">
			<template v-if="$root.selectedTitle==1">
				<template v-if="$root.docsList.length==0">
					<tr class="tr-greyplug">
						<th colspan="100%" class="ta-center font-up">
							Ничего не найдено
						</th>
					</tr>
				</template>
				<template v-else>
					<template v-for="(item, index) in $root.docsList">
						<vue-item-letterlist v-if="((index >= $root.docsListFirst - 1) && (index <= $root.docsListLast - 1))" :data="item" :date-mode="$root.dateMode"  :index="item.id"/>
					</template>
				</template>
			</template>
			<template v-else-if="$root.selectedTitle==2">
				<template v-if="$root.docsListAll.length==0">
					<tr class="tr-greyplug">
						<th colspan="100%" class="ta-center font-up">
							Ничего не найдено
						</th>
					</tr>
				</template>
				<template v-else>
					<template v-for="(item, index) in $root.docsListAll">
						<vue-item-letterlist v-if="((index >= $root.docsListFirstAll - 1) && (index <= $root.docsListLastAll - 1))" :data="item" :date-mode="$root.dateMode" :index="item.id"/>
					</template>
				</template>
			</template>
		</template>
		<template v-else>
			<tr class="tr-greyplug">
				<th colspan="100%" class="ta-center font-up">
					Ничего не найдено
				</th>
			</tr>
		</template>
	</tbody>
</template>