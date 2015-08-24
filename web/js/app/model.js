var Topic = Backbone.Model.extend({
    defaults: {
        html: {
            short: '',
            long: ''
        }
    }
})

var TopicList = Backbone.Collection.extend({
    model: Topic,
    url: app.baseUrl() + 'api/topics/'
});
