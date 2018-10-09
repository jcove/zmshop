import config from "./config";

export function getCategoryRoute(id) {
    return config.baseApi + '/goods?category_id=' + id;
}