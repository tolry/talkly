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

        var facetCollection = Facetr(this.collection);

        var facetVotes = facetCollection.facet('votes');
        var facetSpeakers = facetCollection.facet('speakers');
        var facetComments = facetCollection.facet('comments');

        _.each(facetCollection.facets(), function(facet) {
            var facet = facet.toJSON();

            this.$el.append('<h2>' + facet.data.label + '</h2>');

            var $ul = $('<ul/>');
            this.$el.append($ul);

            _.each(facet.values, function(item) {
                $li = $("<li>" + item.value + " [" + item.count + "]</li>");
                $ul.append($li);
            }, this);

        }, this);

        facetCollection.sortBy('votes').desc();

        _.each(this.collection.models, function (topic) {
            var topic = topic.toJSON();
            this.$el.append('<p>' + topic.html.short + '</p>');
        }, this);
    }
});
