# HyvaGridSourceProcessorInterface

This interface is implemented by all grid source processors.

Grid source processors are an advanced tool that is only rarely needed.

```php
/**
 * Implement this interface in your module and configure it on a grid:
 *
 * <grid>
 *   <source>
 *     ... other source config ...
 *     <processors>
 *       <processor class="YourModuleHyvaGridProcessorMyGridQueryProcessor"/>
 *     </processors>
 *   </source>
 * </grid>
 */
interface HyvaGridSourceProcessorInterface
{
    /**
     * Provides the ability to mutate the grid $source before the grid data is loaded.
     *
     * The search criteria will already have been applied (if applicable for a given source type).
     * Do not mutate $searchCriteria since that will cause multiple loads (it's signature changes,
     * seeHyvaAdminModelGridSourceSearchCriteriaIdentity).
     *
     * The type of $source is grid configuration dependent (it might be a Select instance, or a
     * collection, or a repository, ...).
     *
     * @param mixed $source
     * @param SearchCriteriaInterface $searchCriteria
     * @param string $gridName
     */
    public function beforeLoad($source, SearchCriteriaInterface $searchCriteria, string $gridName): void;

    /**
     * Provides the ability to change the raw grid result after it is loaded.
     *
     * The method must return the new result or null. The $result type depends on the grid configuration.
     * If null is returned, the result value from before afterLoad is used.
     *
     * Do not mutate $searchCriteria since that will cause multiple loads (because )it's signature change,
     * see note on beforeLoad above).
     *
     * @param mixed $rawResult
     * @param SearchCriteriaInterface $searchCriteria
     * @param string $gridName
     * @return mixed
     */
    public function afterLoad($rawResult, SearchCriteriaInterface $searchCriteria, string $gridName);
}
```

If you require only one of the two methods, consider extending your processor from the `AbstractGridSourceProcessor`, which provides a null-op implementation of the interface, and override only the method you need.