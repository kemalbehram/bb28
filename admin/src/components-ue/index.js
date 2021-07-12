import LettCellItem from './lett-cell-item'
import LettPage from './lett-page'
import LettLoading from './lett-loading'
import LettTab from './lett-tab'
import LettAvatar from './lett-avatar'
import ImagePop from './image-pop'
import ImageGroup from './image-group'
import ImagePreview from './image-preview'
import TableHeader from './table-header'
import ImageUpload from './image-upload'
import MultipteUpload from './multipte-upload/index.vue'
import gridForm from './grid-form.vue'
import TableHeaderNew from './table/header-new'
import TableUser from './table/user'
import TableDate from './table/table-date'

import ChatItemTime from './chat/item-time'
import ChatItemText from './chat/item-text'
import ChatItemImage from './chat/item-image'

export default {
    install(Vue) {
        Vue.component('LettCellItem', LettCellItem)
        Vue.component('LettPage', LettPage)
        Vue.component('LettLoading', LettLoading)
        Vue.component('LettTab', LettTab)
        Vue.component('LettAvatar', LettAvatar)
        Vue.component('ImagePop', ImagePop)
        Vue.component('ImageGroup', ImageGroup)
        Vue.component('ImagePreview', ImagePreview)
        Vue.component('TableHeader', TableHeader)
        Vue.component('ImageUpload', ImageUpload)
        Vue.component('MultipteUpload', MultipteUpload)
        Vue.component('gridForm', gridForm)
        Vue.component('ChatItemTime', ChatItemTime)
        Vue.component('ChatItemText', ChatItemText)
        Vue.component('ChatItemImage', ChatItemImage)
        Vue.component('TableHeaderNew', TableHeaderNew)
        Vue.component('TableUser', TableUser)
        Vue.component('TableDate', TableDate)
    },
}
