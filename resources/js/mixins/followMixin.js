import api from "@/api";
import { mergeDeep } from "@/helpers/utils";
export default {
  methods: {
    _replaceInArray(arr, ob) {
      // return new array with replaced item
      const newArr = arr.map(i => {
        if (i.id === ob.id) {
          return mergeDeep(i, ob);
        }
        return i;
      });
      return newArr;
    },

    async _followUser(item, user_id) {
      try {
        const res = await this._auth(() => api.subscribe({ user_id }));
        if (Array.isArray(item)) {
          // replace item with new array of updated data
          const newItem = this._replaceInArray(item, res);
          return newItem;
        } else {
          // update values of existing object
          mergeDeep(item, res);
        }
      } catch (err) {
        throw err;
      }
    }
  }
};
