var SearchView = Backbone.View.extend({
    el: $('.topics'),
    initialize: function() {
        _.bindAll(this, 'render'); // fixes loss of context for 'this' within methods

        var self = this;

        this.collection = new TopicList();
        this.collection.fetch({
            success: function() {
                self.render();
            }
        });
    },
    render: function() {
        this.$el.html("");

        _.each(this.collection.models, function (topic) {
            var topic = topic.toJSON();
            this.$el.append('<p>' + topic.html.short + '</p>');
        }, this);
    }
});
