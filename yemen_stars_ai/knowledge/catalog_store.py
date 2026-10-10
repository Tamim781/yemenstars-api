# -*- coding: utf-8 -*-
import json
import logging
from pathlib import Path
from typing import Any, Dict, List, Optional

logger = logging.getLogger("yemen_stars_ai.knowledge")


class CatalogStore:
    def __init__(self, catalog_path: Optional[Path] = None):
        self.catalog_path = catalog_path or (Path(__file__).parent / "restaurant_catalog.json")
        if not self.catalog_path.exists():
            self.catalog_path = Path(__file__).parent.parent / "restaurant_catalog.json"
        self._data = self._load()

    def _load(self) -> Dict[str, Any]:
        try:
            with self.catalog_path.open("r", encoding="utf-8") as file:
                data = json.load(file)
                logger.info("Loaded restaurant catalog: %d products", len(data.get("products", [])))
                return data
        except Exception as exc:
            logger.error("Could not load catalog: %s", exc)
            return {"restaurant_info": {}, "products": [], "fish_species": []}

    @property
    def restaurant_info(self) -> Dict[str, Any]:
        return self._data.get("restaurant_info", {})

    @property
    def products(self) -> List[Dict[str, Any]]:
        return self._data.get("products", [])

    @property
    def fish_species(self) -> List[Dict[str, Any]]:
        return self._data.get("fish_species", [])

    def get_factual_context(self) -> str:
        info = self.restaurant_info
        lines = [
            "بيانات المطعم الرسمية:",
            f"الاسم: {info.get('name', '')}",
            f"المدينة: {info.get('city', '')}",
            f"الموقع: {info.get('location', '')}",
            f"الهاتف: {', '.join(map(str, info.get('phones', [])))}",
            f"ساعات العمل: {info.get('working_hours', '')}",
            f"التوصيل: {'متاح' if info.get('delivery_available') else 'غير مذكور'}",
            f"واتساب: {info.get('whatsapp', '')}",
            "",
            "الأصناف والأسعار (بالريال اليمني):",
        ]
        for product in self.products:
            lines.append(
                f"- [{product.get('id')}] {product.get('name')} | "
                f"{product.get('price')} ريال | الوحدة: {product.get('unit', '')} | "
                f"القسم: {product.get('category_name', '')} | {product.get('description', '')}"
            )
        if self.fish_species:
            lines.append("\nمعلومات الأنواع المسجلة:")
            for fish in self.fish_species:
                lines.append(
                    f"- {fish.get('name')}: الفئة={fish.get('category', '')}; "
                    f"طرق التحضير={fish.get('recommended_cooking', '')}; "
                    f"القوام={fish.get('texture', '')}; النكهة={fish.get('taste_profile', '')}"
                )
        return "\n".join(lines)

    def reload(self) -> None:
        self._data = self._load()


catalog_store = CatalogStore()
