<script setup>
  import Layout from '../components/Layout.vue';
  import CardFruit from '../components/CardFruit.vue';
  import IconSearch from '../components/icons/IconSearch.vue';

  import { ref, unref, watchEffect } from 'vue';
import { ElNotification } from 'element-plus';

  const props = defineProps({
      fruits: Array,
      total: Number,
      currentPage: {
        type: Number,
        default: 1
      },
      isSignedIn: {
        type: Boolean,
        default: true
      },
      user: {
        type: Object
      }
  });

  const activeLikeBtnRef = ref(null);
  const warningPopoverRef = ref(null);
  const signinWarningShown = ref(false);
  const likeNoticeEffect = ref('liked');
  const likedNoticeShown = ref(false);

  const currentPage = ref(props.currentPage);
  const fruitList = ref([...props.fruits]);
  const totalFruits = ref(props.total);
  
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const searchQuery = urlParams.get('search')
  const searchStr = ref(searchQuery || '')
  const activeSearchStr = ref(searchQuery || '')

  const fetchFruits = async (query) => {
    try {
      const res = await fetch(`${baseUrl}/fruits?${query}`)
      const data = await res.json();
      fruitList.value = data.fruits;
      totalFruits.value = data.total;
    }catch(e){
      console.log(e)
    }
  }

  let likeNoticeTimerId = null;

  const sendLikeUpdateReq = async (fruit, isLiked) => {
    try {
      const res = await fetch(`${baseUrl}/fruits/${fruit.id}/like`, {
        method: 'POST'
      })
      const data = await res.json();
      if(data.success) {
        if(isLiked) {
          likeNoticeEffect.value = 'liked'
        }else{
          likeNoticeEffect.value = 'unliked'
        }

        likedNoticeShown.value = true;
        if(likeNoticeTimerId) clearTimeout(likeNoticeTimerId)
        likeNoticeTimerId = setTimeout(() => {
          likedNoticeShown.value = false;
        }, 3000)
        
        return {
          id: data.id
        }
      }else{
        ElNotification({
          title: 'Error',
          message: data.message,
          type: 'error',
        })
      }
    }catch(e) {

    }
  }

  watchEffect(() => {
    const query = new URLSearchParams({
      search: activeSearchStr.value,
      page: currentPage.value
    });
    fetchFruits(query)
  })

  const onLikeToggle = async (btnRef, index) => {
    activeLikeBtnRef.value = btnRef;
    if(!props.isSignedIn) {
      signinWarningShown.value = true;
    }else if(fruitList.value[index].likes.length) {
      await sendLikeUpdateReq(fruitList.value[index], false);
      fruitList.value[index].likes = []
    }else{
      let d = await sendLikeUpdateReq(fruitList.value[index], true);
      fruitList.value[index].likes = [{
        id: d.id
      }];
    }
  }

  const onPageChange = () => {
    const query = new URLSearchParams({
      search: activeSearchStr.value,
      page: currentPage.value
    });
    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + `?${query}`;
    if (history.pushState) {
      window.history.pushState({path:newurl},'',newurl);
    }else{
      window.location.href = newurl;
    }
  }

  const onClickOutside = (e) => {
    const unrefed = unref(warningPopoverRef);
    if(!unrefed.popperRef.contentRef.contains(e.target)) {
      signinWarningShown.value = false
    }
  }

  const onClickSearch = () => {
    activeSearchStr.value = searchStr.value;
    onPageChange()
  }

  const onSubmit = (e) => {
    e.preventDefault();
    onClickSearch();
  }

  window.addEventListener('popstate', function() {
    var currentPageUrl = document.URL;
    var url = new URL(currentPageUrl);
    var changedPage = url.searchParams.get("page");
    var search = url.searchParams.get('search');
    activeSearchStr.value = search ? search : '';
    searchStr.value = search ? search : '';
    currentPage.value = changedPage ? parseInt(changedPage) : 1
  });
</script>

<template>
  <Layout :user="props.user">
    <h1>Fruit list</h1>
    <el-form @submit="onSubmit">
      <el-form-item>
        <el-input v-model="searchStr" placeholder="Search by name or family">
          <template #append>
            <el-button :icon="IconSearch" @click="onClickSearch"/>
          </template>
        </el-input>
      </el-form-item>
    </el-form>
    <el-row :gutter="20">
      <el-col
        v-for="(fruit, index) in fruitList" :key="fruit.id"
        :span="8"
        :xs="12"
        class="fruit-card-wrapper"
      >
        <CardFruit :fruit="fruit" @like-toggle="(btnRef) => {
          onLikeToggle(btnRef, index)
        }" @clickedOutside="onClickOutside"/>
      </el-col>
    </el-row>
    <el-pagination background layout="prev, pager, next" v-model:current-page="currentPage" :page-size="10" :total="totalFruits" @current-change="onPageChange" />
    <el-popover
      ref="warningPopoverRef"
      :visible="signinWarningShown"
      :virtual-ref="activeLikeBtnRef"
      virtual-triggering
      placement="bottom"
      title="Warning!"
      :width="200"
      trigger="click"
    >
      Signin required!
    </el-popover>
    <el-tooltip
      :visible="likedNoticeShown"
      :content="likeNoticeEffect == 'liked' ? 'Liked' : 'Unliked'"
      placement="left"
      :effect="likeNoticeEffect"
      :virtual-ref="activeLikeBtnRef"
      virtual-triggering
    />
  </Layout>
</template>