 const imagesPrefix =
  "https://raw.githubusercontent.com/creativetimofficial/public-assets/master/material-design-system/presentation/sections";

import imgPricing from "../../../../assets/img/pricing.png";
import imgFeatures from "../../../../assets/img/features.png";
import imgBlogPosts from "../../../../assets/img/blog-posts.png";
import imgTestimonials from "../../../../assets/img/testimonials.png";
import imgTeam from "../../../../assets/img/team.png";
import imgStat from "../../../../assets/img/stat.png";
import imgContent from "../../../../assets/img/content.png";

export default [
  {
    heading: "Design Blocks",
    description:
      "A selection of 45 page sections that fit perfectly in any combination",
    items: [
      {
        image: `${imagesPrefix}/headers.jpg`,
        title: "Page Headers",
        subtitle: "10 Examples",
        route: "",
        pro: false
      },
      {
        image: imgFeatures,
        title: "Features",
        subtitle: "14 Examples",
        route: "",
        pro: false
      },
      {
        image: imgPricing,
        title: "Pricing",
        subtitle: "8 Examples",
        route: "presentation",
        pro: true
      },
      {
        image: `${imagesPrefix}/faq.jpg`,
        title: "FAQ",
        subtitle: "1 Example",
        route: "presentation",
        pro: true
      },
      {
        image: imgBlogPosts,
        title: "Blog Posts",
        subtitle: "11 Examples",
        route: "presentation",
        pro: true
      },
      {
        image: imgTestimonials,
        title: "Testimonials",
        subtitle: "11 Examples",
        route: "presentation",
        pro: true
      },
      {
        image: imgTeam,
        title: "Teams",
        subtitle: "6 Examples",
        route: "presentation",
        pro: true
      },
      {
        image: imgStat,
        title: "Stats",
        subtitle: "3 Examples",
        route: "presentation",
        pro: true
      },
      {
        image: `${imagesPrefix}/call-to-action.jpg`,
        title: "Call to Actions",
        subtitle: "8 Examples",
        route: "presentation",
        pro: true
      },
      {
        image: `${imagesPrefix}/projects.jpg`,
        title: "Applications",
        subtitle: "6 Examples",
        route: "presentation",
        pro: true
      },
      {
        image: `${imagesPrefix}/logo-area.jpg`,
        title: "Logo Areas",
        subtitle: "4 Examples",
        route: "presentation",
        pro: true
      },
      {
        image: `${imagesPrefix}/footers.jpg`,
        title: "Footers",
        subtitle: "10 Examples",
        route: "presentation",
        pro: true
      },
      {
        image: `${imagesPrefix}/general-cards.jpg`,
        title: "General Cards",
        subtitle: "9 Examples",
        route: "presentation",
        pro: true
      },
      {
        image: imgContent,
        title: "Content Sections",
        subtitle: "8 Examples",
        route: "presentation",
        pro: true
      }
    ]
  },
];
